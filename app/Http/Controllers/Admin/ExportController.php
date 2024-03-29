<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Invoice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;


class ExportController extends Controller
{

	public $companyId = 9;

	public function export(Request $request)
	{

		$data = $request->all();

		$xml = new \DOMDocument('1.0', 'utf-8');


		$dataroot = $xml->createElement('dataroot');
		$dataroot = $xml->appendChild($dataroot);

		$tjDocument = $xml->createElement('tjDocument');
		$tjDocument = $dataroot->appendChild($tjDocument);

		$xmlAttribute = $xml->createAttribute('Version');
		$xmlAttribute->value = 'TJ5.5.101';
		$tjDocument->appendChild($xmlAttribute);

		$tjResponse = $xml->createElement('tjResponse');
		$tjResponse = $dataroot->appendChild($tjResponse);

		$xmlAttribute = $xml->createAttribute('Version');
		$xmlAttribute->value = 'TJ5.5.101';
		$tjResponse->appendChild($xmlAttribute);

		$xmlAttribute = $xml->createAttribute('RequestID');
		$xmlAttribute->value = 'FinancialDoc_2';
		$tjResponse->appendChild($xmlAttribute);

		$xmlAttribute = $xml->createAttribute('Structure');
		$xmlAttribute->value = 'Tree';
		$tjResponse->appendChild($xmlAttribute);

		$xmlAttribute = $xml->createAttribute('Operation');
		$xmlAttribute->value = 'Read';
		$tjResponse->appendChild($xmlAttribute);

		$xmlAttribute = $xml->createAttribute('Name');
		$xmlAttribute->value = 'FinancialDoc';
		$tjResponse->appendChild($xmlAttribute);


		$from = isset($data['from']) ? $data['from'] : '2015-01-01';
		$to = isset($data['to']) ? $data['to'] : '2020-01-01';
		$company_id = isset($data['company_id']) ? $data['company_id'] : 'xx';

//        dd($company_id);
		$from = \Carbon\Carbon::parse($from);
		$to = \Carbon\Carbon::parse($to);


		$invoices = \DB::select(
			"
SELECT i.*, il.quantity, il.vat_id, SUM(ROUND(quantity * price,2)) AS sum, vats.rate AS rate, vats.name AS vats_name,
partners.name AS p_name, partners.registration_number AS p_regnumber, partners.vat_number AS p_vatnumber,
companies.title AS c_name, companies.registration_number AS c_regnumber,
currencies.name AS currency_name,
invoice_types.title AS type_name,
structuralunits.title AS structuralunit


FROM invoices AS i
LEFT JOIN invoice_lines AS il
ON (i.id = il.invoice_id)

LEFT JOIN vats
ON (il.vat_id = vats.id)

LEFT JOIN partners
ON (i.partner_id =  partners.id)

LEFT JOIN companies
ON (i.company_id =  companies.id)

LEFT JOIN currencies
ON (i.currency_id =  currencies.id)

LEFT JOIN invoice_types
ON (i.invoicetype_id =  invoice_types.id)

LEFT JOIN structuralunits
ON (i.structuralunit_id =  structuralunits.id)

where i.company_id = '".$company_id."'


AND i.date >= '".$from."'

AND i.date <= '".$to."'
GROUP BY il.invoice_id, il.vat_id
        "
		);

		$lastId = '';
		foreach ($invoices as $invoice) {


			$invoice = (array)$invoice;

			if ($invoice['id'] != $lastId) {

				$financialDoc = $xml->createElement('FinancialDoc');
				$financialDoc = $tjResponse->appendChild($financialDoc);

				//---docNo
				$docNo = $xml->createElement('DocNo');
				$docNo = $financialDoc->appendChild($docNo);

                $type = ($invoice['type_name'] ?? null) === 'avanss' ? 'avanss ' : '';

				$text = $xml->createTextNode($type.$invoice['number']);
				$docNo->appendChild($text);

				//--DocSerial
				$docNoSerial = $xml->createElement('DocNoSerial');
				$docNoSerial = $financialDoc->appendChild($docNoSerial);
				$text = $xml->createTextNode('');
				$docNoSerial->appendChild($text);

				//--DocDate
				$docDate = $xml->createElement('DocDate');
				$docDate = $financialDoc->appendChild($docDate);
//                $text = $xml->createTextNode(\Carbon\Carbon::createFromFormat('d.m.Y', $invoice['date'])->format('Y-m-d'));
				$text = $xml->createTextNode($invoice['date']);
				$docDate->appendChild($text);

				//--DocGroupAbbreviation
				$DocGroupAbbreviation = $xml->createElement(
					'DocGroupAbbreviation'
				);
				$DocGroupAbbreviation = $financialDoc->appendChild(
					$DocGroupAbbreviation
				);
				$text = $xml->createTextNode('D');
				$DocGroupAbbreviation->appendChild($text);

				//--DocCurrency
				$DocCurrency = $xml->createElement('DocCurrency');
				$DocCurrency = $financialDoc->appendChild($DocCurrency);
				$text = $xml->createTextNode($invoice['currency_name']);
				$DocCurrency->appendChild($text);

				//--DocAmount
				$DocAmount = $xml->createElement('DocAmount');
				$DocAmount = $financialDoc->appendChild($DocAmount);
				$text = $xml->createTextNode($invoice['amount_total']);
				$DocAmount->appendChild($text);

				//--DocCompanyVatNoCountryCode
				$DocCompanyVatNoCountryCode = $xml->createElement(
					'DocCompanyVatNoCountryCode'
				);
				$DocCompanyVatNoCountryCode = $financialDoc->appendChild(
					$DocCompanyVatNoCountryCode
				);
				$text = $xml->createTextNode('LV');
				$DocCompanyVatNoCountryCode->appendChild($text);


				//--DocCompanyVatNo
				$DocCompanyVatNo = $xml->createElement('DocCompanyVatNo');
				$DocCompanyVatNo = $financialDoc->appendChild($DocCompanyVatNo);
				$text = $xml->createTextNode('');//------
				$DocCompanyVatNo->appendChild($text);

				//--DocPartnerName
				$DocPartnerName = $xml->createElement('DocPartnerName');
				$DocPartnerName = $financialDoc->appendChild($DocPartnerName);
				$text = $xml->createTextNode($invoice['p_name']);
				$DocPartnerName->appendChild($text);

				//--DocPartnerVatNoCountryCode
				$DocPartnerVatNoCountryCode = $xml->createElement(
					'DocPartnerVatNoCountryCode'
				);
				$DocPartnerVatNoCountryCode = $financialDoc->appendChild(
					$DocPartnerVatNoCountryCode
				);
				$text = $xml->createTextNode('LV');
				$DocPartnerVatNoCountryCode->appendChild($text);

				//--DocPartnerRegistrationNo
				$DocPartnerRegistrationNo = $xml->createElement(
					'DocPartnerRegistrationNo'
				);
				$DocPartnerRegistrationNo = $financialDoc->appendChild(
					$DocPartnerRegistrationNo
				);
				$text = $xml->createTextNode($invoice['p_regnumber']);
				$DocPartnerRegistrationNo->appendChild($text);

				//--DocPartnerVatNo
				$DocPartnerVatNo = $xml->createElement('DocPartnerVatNo');
				$DocPartnerVatNo = $financialDoc->appendChild($DocPartnerVatNo);
				$text = $xml->createTextNode($invoice['p_vatnumber']);
				$DocPartnerVatNo->appendChild($text);

				//--DocDisbursementNoticeID
				$DocDisbursementNoticeID = $xml->createElement(
					'DocDisbursementNoticeID'
				);
				$DocDisbursementNoticeID = $financialDoc->appendChild(
					$DocDisbursementNoticeID
				);
				$text = $xml->createTextNode('');
				$DocDisbursementNoticeID->appendChild($text);

				//--DocDisbursementTerm
				$DocDisbursementTerm = $xml->createElement(
					'DocDisbursementTerm'
				);
				$DocDisbursementTerm = $financialDoc->appendChild(
					$DocDisbursementTerm
				);
				$text = $xml->createTextNode(
					\Carbon\Carbon::createFromFormat(
						'Y-m-d', $invoice['payment_date']
					)->format('Y-m-d')
				);//++++++++++++++++++++++++++


				$DocDisbursementTerm->appendChild($text);

				//--DocComments
				$DocComments = $xml->createElement('DocComments');
				$DocComments = $financialDoc->appendChild($DocComments);
				$text = $xml->createTextNode($invoice['details_self']);
				$DocComments->appendChild($text);


				$lastId = $invoice['id'];
			}
			//--line starts
//            foreach ($invoice['invoice_lines'] as $line)
//            {

			//--FinancialDocLine
			$FinancialDocLine = $xml->createElement('FinancialDocLine');
			$FinancialDocLine = $financialDoc->appendChild($FinancialDocLine);

			//--LineSupplementaryNoticeID
			$LineSupplementaryNoticeID = $xml->createElement(
				'LineSupplementaryNoticeID'
			);
			$LineSupplementaryNoticeID = $FinancialDocLine->appendChild(
				$LineSupplementaryNoticeID
			);
			$text = $xml->createTextNode('1');
			$LineSupplementaryNoticeID->appendChild($text);

			//--LineCurrency
			$LineCurrency = $xml->createElement('LineCurrency');
			$LineCurrency = $FinancialDocLine->appendChild($LineCurrency);
			$text = $xml->createTextNode($invoice['currency_name']);
			$LineCurrency->appendChild($text);

			//--LineAmount
			$LineAmount = $xml->createElement('LineAmount');
			$LineAmount = $FinancialDocLine->appendChild($LineAmount);
			$text = $xml->createTextNode($invoice['sum']);
			$LineAmount->appendChild($text);

			//--LineDebetAccountCode
			$LineDebetAccountCode = $xml->createElement('LineDebetAccountCode');
			$LineDebetAccountCode = $FinancialDocLine->appendChild(
				$LineDebetAccountCode
			);

			$debitAccount = '2310x '.($invoice['structuralunit'] ?? null);

			$text = $xml->createTextNode($debitAccount);
			$LineDebetAccountCode->appendChild($text);

			//--LineCreditAccountCode
			$LineCreditAccountCode = $xml->createElement(
				'LineCreditAccountCode'
			);
			$LineCreditAccountCode = $FinancialDocLine->appendChild(
				$LineCreditAccountCode
			);
			$text = $xml->createTextNode(
				'61-'.$invoice['details_self'].' VAT name: '
				.$invoice['vats_name'].'; rate: '.$invoice['rate']
			);
			$LineCreditAccountCode->appendChild($text);

			//--LineVatRate
			$LineVatRate = $xml->createElement('LineVatRate');
			$LineVatRate = $FinancialDocLine->appendChild($LineVatRate);
			$text = $xml->createTextNode($invoice['rate'] * 100);
			$LineVatRate->appendChild($text);
			//            }
			// --line finish

//            PVN line
			$FinancialDocLine = $xml->createElement('FinancialDocLine');
			$FinancialDocLine = $financialDoc->appendChild($FinancialDocLine);

			//--LineSupplementaryNoticeID
			$LineSupplementaryNoticeID = $xml->createElement(
				'LineSupplementaryNoticeID'
			);
			$LineSupplementaryNoticeID = $FinancialDocLine->appendChild(
				$LineSupplementaryNoticeID
			);
			$text = $xml->createTextNode('1');
			$LineSupplementaryNoticeID->appendChild($text);

			//--LineCurrency
			$LineCurrency = $xml->createElement('LineCurrency');
			$LineCurrency = $FinancialDocLine->appendChild($LineCurrency);
			$text = $xml->createTextNode($invoice['currency_name']);
			$LineCurrency->appendChild($text);

			//--LineAmount
			$LineAmount = $xml->createElement('LineAmount');
			$LineAmount = $FinancialDocLine->appendChild($LineAmount);
			$text = $xml->createTextNode(
				ROUND($invoice['sum'] * $invoice['rate'], 2)
			);
			$LineAmount->appendChild($text);

			//--LineDebetAccountCode
			$LineDebetAccountCode = $xml->createElement('LineDebetAccountCode');
			$LineDebetAccountCode = $FinancialDocLine->appendChild(
				$LineDebetAccountCode
			);

			$text = $xml->createTextNode($debitAccount);
			$LineDebetAccountCode->appendChild($text);

			//--LineCreditAccountCode
			$LineCreditAccountCode = $xml->createElement(
				'LineCreditAccountCode'
			);
			$LineCreditAccountCode = $FinancialDocLine->appendChild(
				$LineCreditAccountCode
			);
			$text = $xml->createTextNode('5721x');
			$LineCreditAccountCode->appendChild($text);

			//--LineVatRate
			$LineVatRate = $xml->createElement('LineVatRate');
			$LineVatRate = $FinancialDocLine->appendChild($LineVatRate);
			$text = $xml->createTextNode($invoice['rate'] * 100);
			$LineVatRate->appendChild($text);
			//PVN line

		}

		$xml->save("test.xml");

		$companies = Company::orderBy('title', 'asc')->get();


		return view('admin.export.index', compact('companies', 'data'));
	}
}