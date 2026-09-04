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
}
