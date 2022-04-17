<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Company;
use App\Calendar;
use App\Employee;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\EmployeeHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class VatController extends Controller
{
    private $data = [];

    private $xml;

    public function __construct(Request $request){

        $this->xml = new \DOMDocument('1.0', 'utf-8');

        $map = [
            'company_id' => 'company_id',
            'from' => 'from',
            'to' => 'to',
            'payable' => 'payable',
        ];

        foreach ($map as $key => $value){
            $this->data[$key] = $request->get($value);
        }
    }


    public function handle(Request $request)
    {

        $company = Company::where('id', $this->data['company_id'])->first();

        // dump($company);
        //
        //
        // dump($this->data);
        $sql = <<<SQL
    SELECT 
        i.id, i.number, i.date, i.vat_number, i.partner_name, i.partner_vat_number,i.currency_rate,
        il.quantity, il.price, il.vat_id,
        v.name as vat_name, v.rate as vat_rate
       
    
    FROM invoices i 

    LEFT JOIN invoice_lines il ON i.id = il.invoice_id
    LEFT JOIN vats v ON il.vat_id = v.id
    
    WHERE i.company_id = :company_id
    AND i.date BETWEEN :from AND :to
    
SQL;

        $result = DB::select($sql, [
            'company_id' => $this->data['company_id'],
            'from' => Carbon::parse($this->data['from'])->format('Y-m-d 00:00:00'),
            'to' => Carbon::parse($this->data['to'])->format('Y-m-d 00:00:00'),
        ]);

        // dump($result);

        $vatReturnData = [];

        foreach ($result as $invoiceLine){

            $amountWithoutVat = ROUND($invoiceLine->quantity / $invoiceLine->currency_rate * $invoiceLine->price, 2);

            $vatReturnData[ strval($invoiceLine->vat_rate)][$invoiceLine->id] = [
                'partner' => $invoiceLine->partner_name,
                'partner_vat' => $invoiceLine->partner_vat_number,
                'amount_without_vat' => ($vatReturnData[strval($invoiceLine->vat_rate)][$invoiceLine->id]['amount_without_vat'] ?? 0) + $amountWithoutVat,
                'amount_vat' => ($vatReturnData[strval($invoiceLine->vat_rate)][$invoiceLine->id]['amount_vat'] ?? 0) + ROUND($amountWithoutVat * $invoiceLine->vat_rate, 2),
                'document_type' => 1,
                'document_number' => $invoiceLine->number,
                'document_date' => $invoiceLine->date,
            ];
        }

        // dump($vatReturnData);

        $vatReturn = [
            'PVN1_1' => [
                'total' =>[
                    'withoutVat' => 0,
                    'vat' => 0,
                ],
                'items' => [],
            ],
            'PVN1_2' => [
                'total' =>[
                    'withoutVat' => 0,
                    'vat' => 0,
                ],
                'items' => [],
            ],
            'PVN1_3' => [
                'total' =>[
                    'withoutVat' => 0,
                    'vat' => 0,
                ],
                'items' => [],
            ],
            'PVN2' => [
                'total' =>[
                    'withoutVat' => 0,
                    'vat' => 0,
                ],
                'items' => [],
            ],
        ];

        $func = function($invoice, &$vatReturn, $part = 'PVN1_1'){
                // if(
                //     $invoice['partner_vat']
                //     &&
                //     preg_replace('/[^A-Z]/', '',$invoice['partner_vat']) == substr($invoice['partner_vat'], 0,2)
                //     &&
                //     preg_replace('/[^A-Z]/', '',$invoice['partner_vat']) != 'GB'
                // ){
                    $vatReturn[$part]['total']['withoutVat'] = ($vatReturn[$part]['total']['withoutVat'] ?? 0) + ROUND($invoice['amount_without_vat'],2);
                    $vatReturn[$part]['total']['vat'] = ($vatReturn[$part]['total']['vat'] ?? 0) +ROUND($invoice['amount_vat'],2);


                    if($part == 'PVN2'){

                        $invoice['amount_without_vat'] = ($vatReturn[$part]['items'][$invoice['partner_vat']]['amount_without_vat'] ?? 0) + $invoice['amount_without_vat'];

                        $vatReturn[$part]['items'][$invoice['partner_vat']] = $invoice;
                    } else {
                        $vatReturn[$part]['items'][] = $invoice;
                    }

                // }
        };

        foreach ($vatReturnData as $vatRate => $invoices){
            if($vatRate === 0){

                foreach($invoices as $invoice){
                    if(
                        $invoice['partner_vat']
                        &&
                        preg_replace('/[^A-Z]/', '',$invoice['partner_vat']) == substr($invoice['partner_vat'], 0,2)
                        &&
                        preg_replace('/[^A-Z]/', '',$invoice['partner_vat']) != 'GB'
                    ){
                        $func($invoice,$vatReturn, 'PVN2');
                    } else {
                        $func($invoice,$vatReturn, 'PVN1_3');
                    }
                }

            }elseif($vatRate === '0.21'){
                foreach($invoices as $invoice){
                    $func($invoice,$vatReturn, 'PVN1_3');
                }
            }
        }

        if($this->data['payable']) {
            $vatReturn['PVN1_1']['total']['vat']        = $vatReturn['PVN1_3']['total']['vat'] - $this->data['payable'];
            $vatReturn['PVN1_1']['total']['withoutVat'] = ROUND($vatReturn['PVN1_1']['total']['vat'] / 0.21, 2);
            $vatReturn['PVN1_1']['items'][]             = [
                "partner" => "",
                "partner_vat" => "",
                "amount_without_vat" => $vatReturn['PVN1_1']['total']['withoutVat'],
                "amount_vat" => $vatReturn['PVN1_1']['total']['vat'],
                "document_type" => 'T',
                "document_number" => "",
                "document_date" => "",
            ];
        }


        // dump($vatReturn);


        $DeclarationFile = $this->xmlCreateElement('DeclarationFile');
        $this->xml->appendChild($DeclarationFile);

        $Declaration = $this->xmlCreateElement('Declaration', null, ['id'=>'DEC']);
        $DeclarationFile->appendChild($Declaration);

        $DokPVNv6 = $this->xmlCreateElement('DokPVNv6', null, [
            'xmlns:xsd' => 'http://www.w3.org/2001/XMLSchema',
            'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
        ]);

        $Declaration->appendChild($DokPVNv6);

        $DokPVNv6->appendChild($this->xmlCreateElement('Precizejums', 'false'));
        $DokPVNv6->appendChild($this->xmlCreateElement('PrecizejamaisDokuments', null, ['xsi:nil'=> 'true']));
        // $DokPVNv6->appendChild($this->xmlCreateElement('Id', null));
        // $DokPVNv6->appendChild($this->xmlCreateElement('UID', null));
        $DokPVNv6->appendChild($this->xmlCreateElement('NmrKods', $company->registration_number));
        $DokPVNv6->appendChild($this->xmlCreateElement('ParskGads', Carbon::parse($this->data['to'])->format('Y')));
        $DokPVNv6->appendChild($this->xmlCreateElement('ParskMen', Carbon::parse($this->data['to'])->format('m')));
        $DokPVNv6->appendChild($this->xmlCreateElement('ParskCeturksnis', null, ['xsi:nil'=> 'true']));
        $DokPVNv6->appendChild($this->xmlCreateElement('TaksPusgads', null, ['xsi:nil'=> 'true']));
        $DokPVNv6->appendChild($this->xmlCreateElement('Epasts', 'agris@mail.com', []));
        $DokPVNv6->appendChild($this->xmlCreateElement('Talrunis', '28323111', []));
        $DokPVNv6->appendChild($this->xmlCreateElement('Sagatavotajs', 'Agris Markus', []));
        $DokPVNv6->appendChild($this->xmlCreateElement('SummaParm', null, ['xsi:nil'=> 'true']));
        $DokPVNv6->appendChild($this->xmlCreateElement('ParmaksUzKontu', null, ['xsi:nil'=> 'true']));
        $DokPVNv6->appendChild($this->xmlCreateElement('ParmaksUzKontuSumma', null, ['xsi:nil'=> 'true']));

        $PVN = $this->xmlCreateElement('PVN');
        $DokPVNv6->appendChild($PVN);
        $PVN->appendChild($this->xmlCreateElement('R41', ROUND($vatReturn['PVN1_3']['total']['vat'] /0.21, 2), []));
        $PVN->appendChild($this->xmlCreateElement('R411', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R42', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R421', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R44', '0', []));
        $PVN->appendChild($this->xmlCreateElement('R45', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R451', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R46', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R47', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R48', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R481', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R43', '0', []));
        $PVN->appendChild($this->xmlCreateElement('R482', $vatReturn['PVN2']['total']['withoutVat'] + ($vatReturn['PVN1_3']['total']['withoutVat'] - ($vatReturn['PVN1_3']['total']['vat'] / 0.21) ), []));
        $PVN->appendChild($this->xmlCreateElement('R49', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R50', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R51', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R511', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R52', $vatReturn['PVN1_3']['total']['vat'], []));
        $PVN->appendChild($this->xmlCreateElement('R53', '0.00', []));
        $PVN->appendChild($this->xmlCreateElement('R531', '0.00', []));
        $PVN->appendChild($this->xmlCreateElement('R54', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R55', '0.00', []));
        $PVN->appendChild($this->xmlCreateElement('R56', '0.00', []));
        $PVN->appendChild($this->xmlCreateElement('R561', '0.00', []));
        $PVN->appendChild($this->xmlCreateElement('R57', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R61', '0.00', []));
        $PVN->appendChild($this->xmlCreateElement('R62', $vatReturn['PVN1_1']['total']['vat'], []));
        $PVN->appendChild($this->xmlCreateElement('R63', '0.00', []));
        $PVN->appendChild($this->xmlCreateElement('R64', '0.00', []));
        $PVN->appendChild($this->xmlCreateElement('R65', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R66', null, ['xsi:nil'=> 'true']));
        $PVN->appendChild($this->xmlCreateElement('R67', null, ['xsi:nil'=> 'true']));

        $PVN1I = $this->xmlCreateElement('PVN1I');
        $DokPVNv6->appendChild($PVN1I);



        foreach ($vatReturn['PVN1_1']['items'] as $item){
            $R = $this->xmlCreateElement('R');
            $PVN1I->appendChild($R);
            // $R->appendChild($this->xmlCreateElement('Npk', '1'));
            $R->appendChild($this->xmlCreateElement('DpValsts'));
            $R->appendChild($this->xmlCreateElement('DpNumurs'));
            $R->appendChild($this->xmlCreateElement('DpNosaukums'));
            $R->appendChild($this->xmlCreateElement('DarVeids', $item['document_type'])); // T
            $R->appendChild($this->xmlCreateElement('VertibaBezPvn', $item['amount_without_vat']));
            $R->appendChild($this->xmlCreateElement('PvnVertiba', $item['amount_vat']));
            $R->appendChild($this->xmlCreateElement('DokVeids'));
            $R->appendChild($this->xmlCreateElement('DokNumurs'));
            $R->appendChild($this->xmlCreateElement('DokDatums', null, ['xsi:nil'=>'true']));
        }


        // $R = $this->xmlCreateElement('R');
        // $PVN1I->appendChild($R);
        // // $R->appendChild($this->xmlCreateElement('Npk', '2'));
        // $R->appendChild($this->xmlCreateElement('DpValsts', 'LV'));
        // $R->appendChild($this->xmlCreateElement('DpNumurs', '40003135880'));
        // $R->appendChild($this->xmlCreateElement('DpNosaukums', 'Biznesa augstskola Turība, SIA'));
        // $R->appendChild($this->xmlCreateElement('DarVeids', 'A'));
        // $R->appendChild($this->xmlCreateElement('VertibaBezPvn', '285.52'));
        // $R->appendChild($this->xmlCreateElement('PvnVertiba', '59.96'));
        // $R->appendChild($this->xmlCreateElement('DokVeids', '5'));
        // $R->appendChild($this->xmlCreateElement('DokNumurs', '0986'));
        // $R->appendChild($this->xmlCreateElement('DokDatums', '2021-12-01'));

        $PVN1II = $this->xmlCreateElement('PVN1II');
        $DokPVNv6->appendChild($PVN1II);

        $PVN1III = $this->xmlCreateElement('PVN1III');
        $DokPVNv6->appendChild($PVN1III);

        foreach ($vatReturn['PVN1_3']['items'] as $item){
            $R = $this->xmlCreateElement('R');
            $PVN1III->appendChild($R);

            $darVeids = $item['amount_vat'] ? '41' : '48.2';

            // $R->appendChild($this->xmlCreateElement('Npk', '1'));
            $R->appendChild($this->xmlCreateElement('DpValsts', $darVeids == '48.2' ? null :substr($item['partner_vat'], 0,2) ));
            $R->appendChild($this->xmlCreateElement('DpNumurs', substr($item['partner_vat'], 2) ));
            $R->appendChild($this->xmlCreateElement('DpNosaukums', $item['partner']));

            $R->appendChild($this->xmlCreateElement('DarVeids', $darVeids));
            $R->appendChild($this->xmlCreateElement('VertibaBezPvn', $item['amount_without_vat']));
            $R->appendChild($this->xmlCreateElement('PvnVertiba', $item['amount_vat']));
            $R->appendChild($this->xmlCreateElement('DokVeids', '1'));
            $R->appendChild($this->xmlCreateElement('DokNumurs', $item['document_number']));
            $R->appendChild($this->xmlCreateElement('DokDatums', $item['document_date']));
        }


        $PVN2 = $this->xmlCreateElement('PVN2');
        $DokPVNv6->appendChild($PVN2);

        foreach ($vatReturn['PVN2']['items'] as $item) {
            $R = $this->xmlCreateElement('R');
            $PVN2->appendChild($R);
            // $R->appendChild($this->xmlCreateElement('Npk', '1'));
            $R->appendChild($this->xmlCreateElement('Valsts', substr($item['partner_vat'], 0,2)));
            $R->appendChild($this->xmlCreateElement('PVNNumurs', substr($item['partner_vat'], 2) ));
            $R->appendChild($this->xmlCreateElement('Summa', $item['amount_without_vat']));
            $R->appendChild($this->xmlCreateElement('Pazime', 'P'));
        }

        $PVN6I = $this->xmlCreateElement('PVN6I');
        $DokPVNv6->appendChild($PVN6I);
        $PVN6I->appendChild($this->xmlCreateElement('R31', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R32', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R33', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R34', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R41', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R42', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R43', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R44', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R51', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R52', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R53', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R54', null, ['xsi:nil'=> 'true']));
        $PVN6I->appendChild($this->xmlCreateElement('R6', null, ['xsi:nil'=> 'true']));

        $DokPVNv6->appendChild($this->xmlCreateElement('PVN6II'));
        $DokPVNv6->appendChild($this->xmlCreateElement('PVN6III'));
        $DokPVNv6->appendChild($this->xmlCreateElement('PVN7I'));

        $Pielikumi = $this->xmlCreateElement('Pielikumi');
        $DokPVNv6->appendChild($Pielikumi);
        $Pielikumi->appendChild($this->xmlCreateElement('CitiPielikumi'));

        // dump($this->xml);

        $this->xml->save('test.xml');

        return $this->index($request);


    }

    public function index(Request $request)
    {
        $data = $this->data;

        $companies = Company::orderBy('title', 'asc')->get();

        return view('admin.vat.index',compact('companies', 'data'));
    }

    private function xmlCreateElement($name, $value = null, $attributes = []){
        $el =  $this->xml->createElement($name, $value);
        foreach ($attributes as $prop => $val){
            $el->setAttribute($prop, $val);
        }

        return $el;
    }

}