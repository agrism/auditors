<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;


class NpiController extends Controller
{

	private $dates = [];
	private $dataArray = [];
	private $i = 0;


	public function create(Request $request)
	{
		return view('admin.npi.create');
	}


	public function handle(Request $request)
	{

//        dd($request->all());

		$validator = Validator::make(
			$request->all(), [
			'invoice_amount' => 'numeric',
			'invoice_date' => 'required|regex:/[0-9]{2}.[0-9]{2}.[0-9]{4}/',
			'period_from' => 'required|regex:/^[0-9]{2}.[0-9]{2}.[0-9]{4}$/',
			'period_till' => 'required|regex:/^[0-9]{2}.[0-9]{2}.[0-9]{4}$/',
			'report_date' => 'required|regex:/^[0-9]{2}.[0-9]{2}.[0-9]{4}$/',
		]
		);

		if ($validator->fails()) {

			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}


		$this->getData($request);

		$fileName = '';

		if ($request->get('submitValue')
			&& $request->get('submitValue') == 'Generate NPI xml'
		) {

			$fileName = $this->createXML();
		}

		return view(
			'admin.npi.create', ['dataArray' => $this->dataArray],
			compact('fileName')
		);
	}


	private function getEndOfMonthDateBeforeReport(
		Carbon $date, Carbon $reportDate, Carbon $lastDate
	) {

		if (count($this->dates) == 0) {
			$this->dates[] = clone $date;
		}


		if ((clone $date)->format('Ym') === (clone $reportDate)->format('Ym')) {
			$this->dates[] = $reportDate;
		}

		$date->endOfMonth();


		if ($date > $lastDate) {
			$this->dates[] = $lastDate;

			return;
		}


		if ($date->diffInDays($reportDate) !== 0) {
			$this->dates[] = $date;
		}

		$this->getEndOfMonthDateBeforeReport(
			(clone $date)->addMinute(), $reportDate, $lastDate
		);
	}

	private function getData(Request $request)
	{
		$data = $request->all();

//        dump($data);

		$dateFrom = \Carbon\Carbon::parse($data['period_from']);
		$dateTill = \Carbon\Carbon::parse($data['period_till']);
		$reportDate = \Carbon\Carbon::parse($data['report_date']);
		$amount = $data['invoice_amount'];

		$allPeriodDays = $dateTill->diffInDays($dateFrom);

		$date = $dateFrom;
		$this->getEndOfMonthDateBeforeReport($date, $reportDate, $dateTill);

//        dump($this->dates);

		$prevDate = null;

		foreach ($this->dates as $key => $date) {

			if (!$prevDate) {
				$prevDate = $date;
				continue;
			}

			$this->dataArray[] = [
				'days' => $date->diffInDays($prevDate),
				'date' => (clone $date)->format("d.m.Y"),
				'dateObj' => (clone $date),
				'partner_name' => $request->get('company'),
				'comment' => $request->get('description'),
				'amount' => Round(
					$amount / $allPeriodDays * $date->diffInDays($prevDate), 2
				),
			];

			$prevDate = $date;
		}

		$totalAmountAssigned = 0;

		foreach ($this->dataArray as $key => $val) {
			$totalAmountAssigned += $val['amount'];
			$this->dataArray[$key]['comment'] = $request->get('description')
				.' (norakst.'.($key + 1).' no '.count($this->dataArray)
				.') Rekins:'.$request->get('invoice_no');
			$this->dataArray[$key]['number'] = $request->get('invoice_no')
				.' norak. '.($key + 1).' no '.count($this->dataArray);

		}


		$difference = $totalAmountAssigned - $amount;
//        dump($difference);

		if ($difference !== 0) {

			$lastValueToModify = array_pop($this->dataArray);
			$lastValueToModify['amount'] -= $difference;
			$this->dataArray[] = $lastValueToModify;


		}

		$totalAmountAssigned = 0;
		foreach ($this->dataArray as $key => $val) {
			$totalAmountAssigned += $val['amount'];
		}

//        dump($totalAmountAssigned);


//        dump($this->dataArray);
	}


	private function createXML()
	{


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

		foreach ($this->dataArray as $data) {
			$financialDoc = $xml->createElement('FinancialDoc');
			$financialDoc = $tjResponse->appendChild($financialDoc);

			//---docNo
			$docNo = $xml->createElement('DocNo');
			$docNo = $financialDoc->appendChild($docNo);
			$text = $xml->createTextNode($data['number']);
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
			$text = $xml->createTextNode($data['dateObj']->format('Y-m-d'));
			$docDate->appendChild($text);

			//--DocGroupAbbreviation
//            $DocGroupAbbreviation = $xml->createElement('DocGroupAbbreviation');
//            $DocGroupAbbreviation = $financialDoc->appendChild($DocGroupAbbreviation);
//            $text = $xml->createTextNode('D');
//            $DocGroupAbbreviation->appendChild($text);

			//--DocCurrency
			$DocCurrency = $xml->createElement('DocCurrency');
			$DocCurrency = $financialDoc->appendChild($DocCurrency);
			$text = $xml->createTextNode('EUR');
			$DocCurrency->appendChild($text);

			//--DocAmount
			$DocAmount = $xml->createElement('DocAmount');
			$DocAmount = $financialDoc->appendChild($DocAmount);
			$text = $xml->createTextNode($data['amount']);
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
			$text = $xml->createTextNode('LV');//------
			$DocCompanyVatNo->appendChild($text);

			//--DocPartnerName
			$DocPartnerName = $xml->createElement('DocPartnerName');
			$DocPartnerName = $financialDoc->appendChild($DocPartnerName);
			$text = $xml->createTextNode($data['partner_name']);
			$DocPartnerName->appendChild($text);

			//--DocPartnerVatNoCountryCode
			$DocPartnerVatNoCountryCode = $xml->createElement(
				'DocPartnerVatNoCountryCode'
			);
			$DocPartnerVatNoCountryCode = $financialDoc->appendChild(
				$DocPartnerVatNoCountryCode
			);
			$text = $xml->createTextNode('');
			$DocPartnerVatNoCountryCode->appendChild($text);

			//--DocPartnerRegistrationNo
			$DocPartnerRegistrationNo = $xml->createElement(
				'DocPartnerRegistrationNo'
			);
			$DocPartnerRegistrationNo = $financialDoc->appendChild(
				$DocPartnerRegistrationNo
			);
			$text = $xml->createTextNode('');
			$DocPartnerRegistrationNo->appendChild($text);

			//--DocPartnerVatNo
			$DocPartnerVatNo = $xml->createElement('DocPartnerVatNo');
			$DocPartnerVatNo = $financialDoc->appendChild($DocPartnerVatNo);
			$text = $xml->createTextNode('');
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
			$DocDisbursementTerm = $xml->createElement('DocDisbursementTerm');
			$DocDisbursementTerm = $financialDoc->appendChild(
				$DocDisbursementTerm
			);
			$text = $xml->createTextNode('');
			$DocDisbursementTerm->appendChild($text);

			//--DocComments
			$DocComments = $xml->createElement('DocComments');
			$DocComments = $financialDoc->appendChild($DocComments);
			$text = $xml->createTextNode($data['comment']);
			$DocComments->appendChild($text);

			//--line starts

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
			$text = $xml->createTextNode('EUR');
			$LineCurrency->appendChild($text);

			//--LineAmount
			$LineAmount = $xml->createElement('LineAmount');
			$LineAmount = $FinancialDocLine->appendChild($LineAmount);
			$text = $xml->createTextNode($data['amount']);
			$LineAmount->appendChild($text);

			//--LineDebetAccountCode
			$LineDebetAccountCode = $xml->createElement('LineDebetAccountCode');
			$LineDebetAccountCode = $FinancialDocLine->appendChild(
				$LineDebetAccountCode
			);
			$text = $xml->createTextNode('71....');
			$LineDebetAccountCode->appendChild($text);

			//--LineCreditAccountCode
			$LineCreditAccountCode = $xml->createElement(
				'LineCreditAccountCode'
			);
			$LineCreditAccountCode = $FinancialDocLine->appendChild(
				$LineCreditAccountCode
			);
			$text = $xml->createTextNode('24....');
			$LineCreditAccountCode->appendChild($text);

			//--LineVatRate
			$LineVatRate = $xml->createElement('LineVatRate');
			$LineVatRate = $FinancialDocLine->appendChild($LineVatRate);
			$text = $xml->createTextNode('');
			$LineVatRate->appendChild($text);


			// --LineComments
			$LineVatRate = $xml->createElement('LineComments');
			$LineVatRate = $FinancialDocLine->appendChild($LineVatRate);
			$text = $xml->createTextNode($data['comment']);
			$LineVatRate->appendChild($text);

			//
			//            }
			// --line finish


			//== line ends
		}

		$fileName = 'npi_'.uniqid().'.xml';

		$xml->save($fileName);

		return $fileName;

	}

}