<?php

namespace App\Http\Controllers\Client;

use Auth;
use App\Services\AuthUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class CashExpenseController extends Controller
{

    public function __construct()
    {

        parent::__construct();

        if ( !AuthUser::instance()
            ->isLoggedIn()
        ) {
            return redirect('/');
        }

        if ( !$this->company = AuthUser::instance()
            ->selectedCompany()
        ) {
            return redirect('/');
        }

        if ( !$this->companyId = AuthUser::instance()
            ->selectedCompanyId()
        ) {
            return redirect('/');
        }
    }


    public function show(
        Request $request,
        $id
    ) {

       if( !DB::table('cash_expenses')->where('id', $id)->where('company_id', $this->companyId)->first()){
           return redirect('/');
       }

        if ($request->get('locale') == 'en') {
            app()->setLocale('en');
        } else {
            app()->setLocale('lv');
        }

        $summary = [
            'total_without_vat' => 0,
            'total_vat' => 0,
            'total_with_vat' => 0,
            'employee_name' => '',
            'date' => '',
            'no' => '',
            'summary' => [],
        ];

        $cashExpenses = DB::table('cash_expenses as ce')
            ->select([
                'ce.*',
                'empl.name as employee_name',
                'cel.date as line_date',
                'cel.no as line_no',
                'cel.document_no as line_document_no',
                'cel.description as line_description',
                'cel.amount_without_vat as line_amount_without_vat',
                'cel.amount_vat as line_amount_vat',
                'cel.amount_with_vat as line_amount_with_vat',
                'p.name as line_partner_name',
                'p.vat_number as line_partner_vat_number',
                'a.code as line_account_code',
                'b.code as line_budget_code',
            ])
            ->leftJoin('employees as empl',
                'empl.id',
                '=',
                'ce.employee_id')
            ->leftJoin('cash_expense_lines as cel',
                'ce.id',
                '=',
                'cel.cash_expenses_id')
            ->leftJoin('partners as p',
                'cel.partner_id',
                '=',
                'p.id')
            ->leftJoin('accounts as a',
                'cel.account_id',
                '=',
                'a.id')
            ->leftJoin('budgets as b',
                'cel.budget_id',
                '=',
                'b.id')
            ->where('ce.id',
                $id)
            ->where('ce.company_id',
                $this->companyId)
            ->orderBy('cel.no', 'asc')
            ->get()
            ->map(function ($record) use (&$summary){
                $record->date = date( 'd.m.Y', strtotime($record->date));
                $record->line_date = $record->line_date ?
                    date( 'd.m.Y', strtotime($record->line_date)) : null;

                $summary['total_without_vat'] += $record->line_amount_without_vat;
                $summary['total_with_vat'] += $record->line_amount_with_vat;
                $summary['total_vat'] += $record->line_amount_vat;
                $summary['date'] = $record->date;
                $summary['no'] = $record->no;
                $summary['employee_name'] = $record->employee_name;

                if(empty($summary['details'][$record->line_account_code . $record->line_budget_code])) {
                    $summary['details'][$record->line_account_code . $record->line_budget_code] = [
                        'account_code' => null,
                        'budget' => null,
                        'amount_without_vat' => 0.00,
                        'vat' => 0.00,
                        'amount_with_vat' => 0.00,
                    ];
                }

                $summary['details'][$record->line_account_code . $record->line_budget_code]['account_code'] = $record->line_account_code;
                $summary['details'][$record->line_account_code . $record->line_budget_code]['budget'] = $record->line_budget_code;
                $summary['details'][$record->line_account_code . $record->line_budget_code]['amount_without_vat'] += $record->line_amount_without_vat;
                $summary['details'][$record->line_account_code . $record->line_budget_code]['vat'] += floatval($record->line_amount_vat);
                $summary['details'][$record->line_account_code . $record->line_budget_code]['amount_with_vat'] += $record->line_amount_with_vat;


                return $record;
            })->filter(function ($record){
                return $record->line_date;
            });

        $data = (object)[
            'summary' => $summary,
            'lines' => $cashExpenses,
        ];

        // return view('client.cash-expenses.show',
        //     compact('data'));

        $pdf = app::make('dompdf.wrapper');

        $pdf->loadview('client.cash-expenses.show',
            compact('data'))
            ->setPaper('a4');


        // $invoiceNumber = $invoice->number;
        // $date          = Carbon\Carbon::createfromformat('d.m.Y',
        //     $invoice->date)
        //     ->format('Y-m-d');

        // $details = $invoice->details_self;
        // $partner = $invoice->partner->name ?? null;

        // $key = strpos($partner,
        //     ',');
        //
        // $partner = substr($partner,
        //     0,
        //     $key);

        app()->setLocale('en');

        // return $pdf->download('avansu_norekins_.pdf');

        return $pdf->download('AN_' . ($cashExpenses[0]->no ?? '') . '_' . ($cashExpenses[0]->date ?? '') . '_' . ($cashExpenses[0]->employee_name ?? '') . '.pdf');
    }
}