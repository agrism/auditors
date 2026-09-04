<?php

namespace Tests\Unit;

use App\Company;
use App\Currency;
use App\Invoice;
use App\InvoiceLine;
use App\Partner;
use App\Services\InvoiceXmlService;
use App\Unit;
use App\Vat;
use DOMDocument;
use Tests\TestCase;

class InvoiceXmlServiceTest extends TestCase
{
    public function test_generate_xml_creates_valid_ubl_peppol_structure()
    {
        $company = new Company();
        $company->forceFill([
            'title' => 'SIA Test Supplier',
            'registration_number' => '40001234567',
            'address' => 'Brivibas iela 1, Riga, LV-1010',
            'bank' => 'Swedbank',
            'swift' => 'HABALV22',
            'account_number' => 'LV00HABA0000000000000',
        ]);
        $company->setRelation('vatNumbers', collect());

        $partner = new Partner();
        $partner->forceFill([
            'name' => 'SIA Test Buyer',
            'registration_number' => '40007654321',
            'vat_number' => 'LV40007654321',
            'address' => 'Valdemara iela 2, Riga, LV-1010',
        ]);

        $currency = new Currency();
        $currency->forceFill(['name' => 'EUR']);

        $unit = new Unit();
        $unit->forceFill(['name' => 'gab.']);

        $vat = new Vat();
        $vat->forceFill(['name' => 'PVN 21%', 'rate' => 21]);

        $line1 = new InvoiceLine();
        $line1->forceFill([
            'title' => 'Consulting Services',
            'quantity' => 10,
            'price' => 50.00,
        ]);
        $line1->setRelation('unit', $unit);
        $line1->setRelation('vat', $vat);

        $line2 = new InvoiceLine();
        $line2->forceFill([
            'title' => 'Software License',
            'quantity' => 1,
            'price' => 200.00,
        ]);
        $line2->setRelation('unit', $unit);
        $line2->setRelation('vat', $vat);

        $invoice = new Invoice();
        $invoice->forceFill([
            'number' => 'INV-2026-001',
            'date' => '04.09.2026',
            'payment_date' => '18.09.2026',
            'vat_number' => 'LV40001234567',
            'details' => 'Payment for IT services',
            'account_number' => 'LV00HABA0000000000000',
            'bank' => 'Swedbank',
            'swift' => 'HABALV22',
            'amount_total' => 847.00,
        ]);

        $invoice->setRelation('company', $company);
        $invoice->setRelation('partner', $partner);
        $invoice->setRelation('currency', $currency);
        $invoice->setRelation('invoiceLines', collect([$line1, $line2]));

        $service = new InvoiceXmlService();
        $xml = $service->generateXml($invoice);

        $this->assertNotEmpty($xml);
        $this->assertStringContainsString('urn:oasis:names:specification:ubl:schema:xsd:Invoice-2', $xml);
        $this->assertStringContainsString('urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0', $xml);
        $this->assertStringContainsString('INV-2026-001', $xml);
        $this->assertStringContainsString('2026-09-04', $xml);
        $this->assertStringContainsString('SIA Test Supplier', $xml);
        $this->assertStringContainsString('SIA Test Buyer', $xml);
        $this->assertStringContainsString('Consulting Services', $xml);
        $this->assertStringContainsString('Software License', $xml);
        $this->assertStringContainsString('LV00HABA0000000000000', $xml);
        $this->assertStringContainsString('700.00', $xml); // Net (500 + 200)
        $this->assertStringContainsString('147.00', $xml); // Tax (21% of 700)
        $this->assertStringContainsString('847.00', $xml); // Gross (700 + 147)

        $doc = new DOMDocument();
        $this->assertTrue($doc->loadXML($xml));
        $this->assertEquals('Invoice', $doc->documentElement->localName);
    }

    public function test_generate_xml_omits_party_tax_scheme_for_non_vat_buyer()
    {
        $company = new Company();
        $company->forceFill([
            'title' => 'LFC Group SIA',
            'registration_number' => '40003624203',
            'address' => 'Dubultu iela 20-7, Rīga, LV1069',
            'bank' => 'Swedbank AS',
            'swift' => 'HABALV22',
            'account_number' => 'LV83HABA0551004219090',
        ]);
        $company->setRelation('vatNumbers', collect());

        $partner = new Partner();
        $partner->forceFill([
            'name' => 'Patērētāju tiesību aizsardzības centrs',
            'registration_number' => '90000068854',
            'vat_number' => '-',
            'address' => 'Talejas iela 1, Rīga, LV-1026',
        ]);

        $currency = new Currency();
        $currency->forceFill(['name' => 'EUR']);

        $unit = new Unit();
        $unit->forceFill(['name' => 'stundas']);

        $vat = new Vat();
        $vat->forceFill(['name' => 'PVN 21%', 'rate' => 21]);

        $line = new InvoiceLine();
        $line->forceFill([
            'title' => 'IT Services',
            'quantity' => 2,
            'price' => 62.00,
        ]);
        $line->setRelation('unit', $unit);
        $line->setRelation('vat', $vat);

        $invoice = new Invoice();
        $invoice->forceFill([
            'number' => 'PTAC/009',
            'date' => '04.09.2026',
            'payment_date' => '18.09.2026',
            'vat_number' => 'LV40003624203',
            'partner_vat_number' => '-',
            'partner_registration_number' => '90000068854',
            'partner_name' => 'Patērētāju tiesību aizsardzības centrs',
            'partner_address' => 'Talejas iela 1, Rīga, LV-1026',
            'account_number' => 'LV83HABA0551004219090',
            'bank' => 'Swedbank AS',
            'swift' => 'HABALV22',
            'amount_total' => 149.92,
        ]);

        $invoice->setRelation('company', $company);
        $invoice->setRelation('partner', $partner);
        $invoice->setRelation('currency', $currency);
        $invoice->setRelation('invoiceLines', collect([$line]));

        $service = new InvoiceXmlService();
        $xml = $service->generateXml($invoice);

        $doc = new DOMDocument();
        $this->assertTrue($doc->loadXML($xml));

        $xpath = new \DOMXPath($doc);
        $xpath->registerNamespace('cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
        $xpath->registerNamespace('cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');

        // Supplier tax scheme must be the 11-digit clean registration number with TAX scheme (LV-037 valid)
        $supplierTaxId = $xpath->query('//cac:AccountingSupplierParty/cac:Party/cac:PartyTaxScheme/cbc:CompanyID');
        $this->assertEquals(1, $supplierTaxId->length);
        $this->assertEquals('40003624203', $supplierTaxId->item(0)->textContent);
        $supplierTaxScheme = $xpath->query('//cac:AccountingSupplierParty/cac:Party/cac:PartyTaxScheme/cac:TaxScheme/cbc:ID');
        $this->assertEquals('TAX', $supplierTaxScheme->item(0)->textContent);

        // Buyer PartyTaxScheme must NOT be present since partner_vat_number is '-'
        $customerTaxScheme = $xpath->query('//cac:AccountingCustomerParty/cac:Party/cac:PartyTaxScheme');
        $this->assertEquals(0, $customerTaxScheme->length);

        // LegalEntity CompanyID should have the clean registration number
        $customerLegalCompId = $xpath->query('//cac:AccountingCustomerParty/cac:Party/cac:PartyLegalEntity/cbc:CompanyID');
        $this->assertEquals(1, $customerLegalCompId->length);
        $this->assertEquals('90000068854', $customerLegalCompId->item(0)->textContent);
    }
}
