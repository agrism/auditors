<?php

namespace App\Services;

use App\Invoice;
use DOMDocument;
use Carbon\Carbon;

class InvoiceXmlService
{
    /**
     * Generate PEPPOL BIS Billing 3.0 (UBL 2.1) XML for an invoice.
     *
     * @param Invoice $invoice
     * @return string
     */
    public function generateXml(Invoice $invoice): string
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElementNS('urn:oasis:names:specification:ubl:schema:xsd:Invoice-2', 'Invoice');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
        $dom->appendChild($root);

        // 1. CustomizationID & ProfileID (PEPPOL BIS Billing 3.0 / EN 16931)
        $this->addCbcElement($dom, $root, 'cbc:CustomizationID', 'urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0');
        $this->addCbcElement($dom, $root, 'cbc:ProfileID', 'urn:fdc:peppol.eu:2017:poacc:billing:01:1.0');

        // 2. Invoice Header
        $this->addCbcElement($dom, $root, 'cbc:ID', $invoice->number ?? 'INV-' . $invoice->id);

        $issueDate = $this->formatDateForXml($invoice->date);
        $this->addCbcElement($dom, $root, 'cbc:IssueDate', $issueDate);

        if (!empty($invoice->payment_date)) {
            $dueDate = $this->formatDateForXml($invoice->payment_date);
            $this->addCbcElement($dom, $root, 'cbc:DueDate', $dueDate);
        }

        $this->addCbcElement($dom, $root, 'cbc:InvoiceTypeCode', '380');

        if (!empty($invoice->details)) {
            $this->addCbcElement($dom, $root, 'cbc:Note', strip_tags($invoice->details));
        }

        $currencyCode = $invoice->currency->name ?? 'EUR';
        $this->addCbcElement($dom, $root, 'cbc:DocumentCurrencyCode', $currencyCode);

        $buyerRef = $invoice->partner_registration_number ?? $invoice->partner->registration_number ?? $invoice->partner_name ?? 'N/A';
        $this->addCbcElement($dom, $root, 'cbc:BuyerReference', $buyerRef);

        // 3. AccountingSupplierParty (Seller)
        $this->appendSupplierParty($dom, $root, $invoice);

        // 4. AccountingCustomerParty (Buyer)
        $this->appendCustomerParty($dom, $root, $invoice);

        // 5. PaymentMeans
        $this->appendPaymentMeans($dom, $root, $invoice);

        // 6. Tax calculations & Totals
        $lineData = [];
        $taxSubtotals = [];
        $totalNet = 0.0;
        $totalTax = 0.0;

        foreach ($invoice->invoiceLines as $index => $line) {
            $quantity = (float)($line->quantity ?: 1);
            $price = (float)($line->price ?: 0);
            $lineNet = round($quantity * $price, 2);
            $totalNet += $lineNet;

            $vatRate = 0.0;
            if ($line->vat) {
                $vatRate = (float)($line->vat->rate ?: 0);
                if ($vatRate < 1 && $vatRate > 0) {
                    $vatRate = $vatRate * 100;
                }
            }

            $catCode = ($vatRate > 0) ? 'S' : 'Z';
            $unitCode = $this->mapUnitCode($line->unit->name ?? '');

            $lineData[] = [
                'index' => $index + 1,
                'title' => strip_tags(str_replace('@br', ' ', $line->title ?: 'Item')),
                'quantity' => $quantity,
                'unitCode' => $unitCode,
                'price' => $price,
                'lineNet' => $lineNet,
                'vatRate' => $vatRate,
                'catCode' => $catCode,
            ];

            $taxKey = $catCode . '_' . number_format($vatRate, 2, '.', '');
            if (!isset($taxSubtotals[$taxKey])) {
                $taxSubtotals[$taxKey] = [
                    'catCode' => $catCode,
                    'vatRate' => $vatRate,
                    'taxableAmount' => 0.0,
                    'taxAmount' => 0.0,
                ];
            }
            $taxSubtotals[$taxKey]['taxableAmount'] += $lineNet;
        }

        foreach ($taxSubtotals as &$sub) {
            $sub['taxAmount'] = round($sub['taxableAmount'] * ($sub['vatRate'] / 100), 2);
            $totalTax += $sub['taxAmount'];
        }
        unset($sub);

        $totalGross = $totalNet + $totalTax;

        // 7. TaxTotal
        $taxTotalElem = $dom->createElement('cac:TaxTotal');
        $taxAmountElem = $this->addCbcElement($dom, $taxTotalElem, 'cbc:TaxAmount', number_format($totalTax, 2, '.', ''));
        $taxAmountElem->setAttribute('currencyID', $currencyCode);

        foreach ($taxSubtotals as $sub) {
            $subtotalElem = $dom->createElement('cac:TaxSubtotal');
            $subTaxable = $this->addCbcElement($dom, $subtotalElem, 'cbc:TaxableAmount', number_format($sub['taxableAmount'], 2, '.', ''));
            $subTaxable->setAttribute('currencyID', $currencyCode);

            $subTax = $this->addCbcElement($dom, $subtotalElem, 'cbc:TaxAmount', number_format($sub['taxAmount'], 2, '.', ''));
            $subTax->setAttribute('currencyID', $currencyCode);

            $taxCatElem = $dom->createElement('cac:TaxCategory');
            $this->addCbcElement($dom, $taxCatElem, 'cbc:ID', $sub['catCode']);
            $this->addCbcElement($dom, $taxCatElem, 'cbc:Percent', number_format($sub['vatRate'], 2, '.', ''));

            $taxSchemeElem = $dom->createElement('cac:TaxScheme');
            $this->addCbcElement($dom, $taxSchemeElem, 'cbc:ID', 'VAT');
            $taxCatElem->appendChild($taxSchemeElem);

            $subtotalElem->appendChild($taxCatElem);
            $taxTotalElem->appendChild($subtotalElem);
        }
        $root->appendChild($taxTotalElem);

        // 8. LegalMonetaryTotal
        $legalTotalElem = $dom->createElement('cac:LegalMonetaryTotal');
        $this->addMonetaryElement($dom, $legalTotalElem, 'cbc:LineExtensionAmount', $totalNet, $currencyCode);
        $this->addMonetaryElement($dom, $legalTotalElem, 'cbc:TaxExclusiveAmount', $totalNet, $currencyCode);
        $this->addMonetaryElement($dom, $legalTotalElem, 'cbc:TaxInclusiveAmount', $totalGross, $currencyCode);
        $this->addMonetaryElement($dom, $legalTotalElem, 'cbc:PayableAmount', $totalGross, $currencyCode);
        $root->appendChild($legalTotalElem);

        // 9. InvoiceLine elements
        foreach ($lineData as $line) {
            $lineElem = $dom->createElement('cac:InvoiceLine');
            $this->addCbcElement($dom, $lineElem, 'cbc:ID', (string)$line['index']);

            $qtyElem = $this->addCbcElement($dom, $lineElem, 'cbc:InvoicedQuantity', number_format($line['quantity'], 2, '.', ''));
            $qtyElem->setAttribute('unitCode', $line['unitCode']);

            $this->addMonetaryElement($dom, $lineElem, 'cbc:LineExtensionAmount', $line['lineNet'], $currencyCode);

            // Item
            $itemElem = $dom->createElement('cac:Item');
            $this->addCbcElement($dom, $itemElem, 'cbc:Description', $line['title']);
            $this->addCbcElement($dom, $itemElem, 'cbc:Name', $line['title']);

            $itemTaxCat = $dom->createElement('cac:ClassifiedTaxCategory');
            $this->addCbcElement($dom, $itemTaxCat, 'cbc:ID', $line['catCode']);
            $this->addCbcElement($dom, $itemTaxCat, 'cbc:Percent', number_format($line['vatRate'], 2, '.', ''));
            $itemTaxScheme = $dom->createElement('cac:TaxScheme');
            $this->addCbcElement($dom, $itemTaxScheme, 'cbc:ID', 'VAT');
            $itemTaxCat->appendChild($itemTaxScheme);
            $itemElem->appendChild($itemTaxCat);
            $lineElem->appendChild($itemElem);

            // Price
            $priceElem = $dom->createElement('cac:Price');
            $this->addMonetaryElement($dom, $priceElem, 'cbc:PriceAmount', $line['price'], $currencyCode);
            $lineElem->appendChild($priceElem);

            $root->appendChild($lineElem);
        }

        return $dom->saveXML();
    }

    protected function appendSupplierParty(DOMDocument $dom, \DOMElement $root, Invoice $invoice): void
    {
        $supplier = $dom->createElement('cac:AccountingSupplierParty');
        $party = $dom->createElement('cac:Party');

        $company = $invoice->company;
        $companyTitle = $company->title ?? 'Supplier';
        $regNumber = preg_replace('/[^0-9A-Za-z]/', '', $company->registration_number ?? '');
        $vatNumber = $invoice->vat_number ?? ($company->vatNumbers->first()->number ?? $company->vat_number ?? '');

        if (!empty($regNumber)) {
            $endpoint = $this->addCbcElement($dom, $party, 'cbc:EndpointID', $regNumber);
            $endpoint->setAttribute('schemeID', '0218');

            $partyId = $dom->createElement('cac:PartyIdentification');
            $this->addCbcElement($dom, $partyId, 'cbc:ID', $regNumber);
            $party->appendChild($partyId);
        }

        $partyName = $dom->createElement('cac:PartyName');
        $this->addCbcElement($dom, $partyName, 'cbc:Name', $companyTitle);
        $party->appendChild($partyName);

        $this->appendPostalAddress($dom, $party, $company->address ?? '');

        // Supplier tax scheme (LV-037 requires 5-11 chars registration/tax number for LV entities)
        $taxId = $this->cleanTaxNumber($vatNumber, $regNumber, 'LV');
        if (!empty($taxId)) {
            $taxScheme = $dom->createElement('cac:PartyTaxScheme');
            $this->addCbcElement($dom, $taxScheme, 'cbc:CompanyID', $taxId);
            $scheme = $dom->createElement('cac:TaxScheme');
            $this->addCbcElement($dom, $scheme, 'cbc:ID', 'VAT');
            $taxScheme->appendChild($scheme);
            $party->appendChild($taxScheme);
        }

        $legalEntity = $dom->createElement('cac:PartyLegalEntity');
        $this->addCbcElement($dom, $legalEntity, 'cbc:RegistrationName', $companyTitle);
        if (!empty($regNumber)) {
            $this->addCbcElement($dom, $legalEntity, 'cbc:CompanyID', $regNumber);
        }
        $party->appendChild($legalEntity);

        $supplier->appendChild($party);
        $root->appendChild($supplier);
    }

    protected function appendCustomerParty(DOMDocument $dom, \DOMElement $root, Invoice $invoice): void
    {
        $customer = $dom->createElement('cac:AccountingCustomerParty');
        $party = $dom->createElement('cac:Party');

        $partner = $invoice->partner;
        $customerName = $invoice->partner_name ?? $partner->name ?? 'Customer';
        $regNumber = preg_replace('/[^0-9A-Za-z]/', '', $invoice->partner_registration_number ?? $partner->registration_number ?? '');
        $rawVatNumber = $invoice->partner_vat_number ?? $partner->vat_number ?? '';
        $customerAddress = $invoice->partner_address ?? $partner->address ?? '';

        if (!empty($regNumber)) {
            $endpoint = $this->addCbcElement($dom, $party, 'cbc:EndpointID', $regNumber);
            $endpoint->setAttribute('schemeID', '0218');

            $partyId = $dom->createElement('cac:PartyIdentification');
            $this->addCbcElement($dom, $partyId, 'cbc:ID', $regNumber);
            $party->appendChild($partyId);
        }

        $partyName = $dom->createElement('cac:PartyName');
        $this->addCbcElement($dom, $partyName, 'cbc:Name', $customerName);
        $party->appendChild($partyName);

        $this->appendPostalAddress($dom, $party, $customerAddress);

        // Buyer PartyTaxScheme is optional in PEPPOL/EN16931. Only include if buyer is a valid VAT payer.
        $customerTaxId = $this->cleanTaxNumber($rawVatNumber, null, 'LV');
        if (!empty($customerTaxId)) {
            $taxScheme = $dom->createElement('cac:PartyTaxScheme');
            $this->addCbcElement($dom, $taxScheme, 'cbc:CompanyID', $customerTaxId);
            $scheme = $dom->createElement('cac:TaxScheme');
            $this->addCbcElement($dom, $scheme, 'cbc:ID', 'VAT');
            $taxScheme->appendChild($scheme);
            $party->appendChild($taxScheme);
        }

        $legalEntity = $dom->createElement('cac:PartyLegalEntity');
        $this->addCbcElement($dom, $legalEntity, 'cbc:RegistrationName', $customerName);
        if (!empty($regNumber)) {
            $this->addCbcElement($dom, $legalEntity, 'cbc:CompanyID', $regNumber);
        }
        $party->appendChild($legalEntity);

        $customer->appendChild($party);
        $root->appendChild($customer);
    }

    protected function appendPaymentMeans(DOMDocument $dom, \DOMElement $root, Invoice $invoice): void
    {
        $accountNumber = $invoice->account_number ?? $invoice->company->account_number ?? '';
        $bankName = $invoice->bank ?? $invoice->company->bank ?? '';
        $swift = $invoice->swift ?? $invoice->company->swift ?? '';

        $paymentMeans = $dom->createElement('cac:PaymentMeans');
        $this->addCbcElement($dom, $paymentMeans, 'cbc:PaymentMeansCode', '30'); // 30 = Credit transfer
        $this->addCbcElement($dom, $paymentMeans, 'cbc:PaymentID', $invoice->number ?? (string)$invoice->id);

        if (!empty($accountNumber)) {
            $account = $dom->createElement('cac:PayeeFinancialAccount');
            $this->addCbcElement($dom, $account, 'cbc:ID', $accountNumber);
            if (!empty($bankName)) {
                $this->addCbcElement($dom, $account, 'cbc:Name', $bankName);
            }
            if (!empty($swift)) {
                $branch = $dom->createElement('cac:FinancialInstitutionBranch');
                $this->addCbcElement($dom, $branch, 'cbc:ID', $swift);
                $account->appendChild($branch);
            }
            $paymentMeans->appendChild($account);
        }

        $root->appendChild($paymentMeans);
    }

    protected function addCbcElement(DOMDocument $dom, \DOMElement $parent, string $name, ?string $value): \DOMElement
    {
        $cleanValue = html_entity_decode((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $elem = $dom->createElement($name);
        $elem->appendChild($dom->createTextNode($cleanValue));
        $parent->appendChild($elem);
        return $elem;
    }

    protected function addMonetaryElement(DOMDocument $dom, \DOMElement $parent, string $name, float $amount, string $currency): \DOMElement
    {
        $formatted = number_format($amount, 2, '.', '');
        $elem = $this->addCbcElement($dom, $parent, $name, $formatted);
        $elem->setAttribute('currencyID', $currency);
        return $elem;
    }

    protected function formatDateForXml(?string $dateStr): string
    {
        if (empty($dateStr)) {
            return Carbon::now()->format('Y-m-d');
        }

        try {
            return Carbon::createFromFormat('d.m.Y', $dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return Carbon::parse($dateStr)->format('Y-m-d');
            } catch (\Exception $e2) {
                return Carbon::now()->format('Y-m-d');
            }
        }
    }

    protected function mapUnitCode(?string $unitName): string
    {
        if (empty($unitName)) {
            return 'C62'; // UN/ECE Recommendation 20 code for Piece/Unit
        }

        $normalized = mb_strtolower(trim($unitName));

        return match ($normalized) {
            'h', 'st.', 'stunda', 'stundas', 'hour', 'hours' => 'HUR',
            'd.', 'diena', 'dienas', 'day', 'days' => 'DAY',
            'mēn.', 'mēnesis', 'mēneši', 'month', 'months' => 'MON',
            'kg', 'kilograms', 'kilogrami' => 'KGM',
            'm', 'metrs', 'metri', 'meter', 'meters' => 'MTR',
            'l', 'litrs', 'litri', 'liter', 'liters' => 'LTR',
            'kompl.', 'komplekts', 'komplekti', 'set' => 'SET',
            'pak.', 'pakalpojums', 'service' => 'E48',
            default => 'C62',
        };
    }

    protected function cleanTaxNumber(?string $vatNumber, ?string $regNumber = null, string $countryCode = 'LV'): ?string
    {
        $raw = trim((string)$vatNumber);

        // Check if empty or explicitly marked as not available
        if (empty($raw) || in_array(mb_strtolower($raw), ['-', 'nav', 'n/a', 'na', 'none', 'null', '0'], true)) {
            if (!empty($regNumber) && $countryCode === 'LV') {
                $cleanedReg = preg_replace('/[^0-9A-Za-z]/', '', $regNumber);
                if (strlen($cleanedReg) >= 5 && strlen($cleanedReg) <= 11) {
                    return $cleanedReg;
                }
            }
            return null;
        }

        $cleaned = preg_replace('/[^0-9A-Za-z]/', '', $raw);

        if ($countryCode === 'LV') {
            // If prefixed with LV, strip it to get the 11-digit national tax number
            if (preg_match('/^LV([0-9A-Za-z]{5,11})$/i', $cleaned, $matches)) {
                return $matches[1];
            }

            if (strlen($cleaned) >= 5 && strlen($cleaned) <= 11) {
                return $cleaned;
            }

            if (!empty($regNumber)) {
                $cleanedReg = preg_replace('/[^0-9A-Za-z]/', '', $regNumber);
                if (strlen($cleanedReg) >= 5 && strlen($cleanedReg) <= 11) {
                    return $cleanedReg;
                }
            }

            return null;
        }

        return strlen($cleaned) >= 3 ? $cleaned : null;
    }

    protected function appendPostalAddress(DOMDocument $dom, \DOMElement $party, ?string $rawAddress, string $countryCode = 'LV'): void
    {
        $address = $dom->createElement('cac:PostalAddress');
        $cleanAddress = trim((string)$rawAddress);

        if (empty($cleanAddress)) {
            $this->addCbcElement($dom, $address, 'cbc:StreetName', 'Latvija');
            $country = $dom->createElement('cac:Country');
            $this->addCbcElement($dom, $country, 'cbc:IdentificationCode', $countryCode);
            $address->appendChild($country);
            $party->appendChild($address);
            return;
        }

        // Try to extract postal code (e.g. LV-1069, LV 1069, LV1069)
        $postalZone = null;
        if (preg_match('/(LV\s*-\s*[0-9]{4}|LV[0-9]{4})/i', $cleanAddress, $matches)) {
            $postalZone = strtoupper(str_replace(' ', '', $matches[1]));
            if (!str_contains($postalZone, '-')) {
                $postalZone = substr($postalZone, 0, 2) . '-' . substr($postalZone, 2);
            }
        }

        // Split by commas if present
        $parts = array_map('trim', explode(',', $cleanAddress));
        $street = $parts[0] ?? $cleanAddress;
        $city = null;

        if (count($parts) > 1) {
            foreach (array_slice($parts, 1) as $part) {
                // If part contains the postal code, strip it to find city
                $cleanedPart = preg_replace('/(LV\s*-\s*[0-9]{4}|LV[0-9]{4})/i', '', $part);
                $cleanedPart = trim($cleanedPart);
                if (!empty($cleanedPart) && empty($city)) {
                    $city = $cleanedPart;
                }
            }
        }

        $this->addCbcElement($dom, $address, 'cbc:StreetName', $street ?: $cleanAddress);
        if (!empty($city)) {
            $this->addCbcElement($dom, $address, 'cbc:CityName', $city);
        }
        if (!empty($postalZone)) {
            $this->addCbcElement($dom, $address, 'cbc:PostalZone', $postalZone);
        }

        $country = $dom->createElement('cac:Country');
        $this->addCbcElement($dom, $country, 'cbc:IdentificationCode', $countryCode);
        $address->appendChild($country);
        $party->appendChild($address);
    }
}
