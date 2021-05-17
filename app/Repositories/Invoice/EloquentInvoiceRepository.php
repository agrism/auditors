<?php namespace App\Repositories\Invoice;

use App\Invoice;
use App\InvoiceType;
use App\Partner;


use App;
use App\Structuralunit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EloquentInvoiceRepository implements InvoiceRepository
{

    public $companyId;
    public $company;

    public $partnerId = null;
    public $details = null;
    public $typeId = null;
    public $structId = null;
    public $dateFrom = null;
    public $dateTo = null;

    public $sortColumn = null;
    public $sortDirection = null;

    private function init()
    {
        $this->company = App\Services\SelectedCompanyService::getCompany();
        if (!isset($this->company->id)) {
            return route('client.companies.index');
        }
        $this->companyId = $this->company->id ?? null;
    }

    public function getInvoices(array $params)
    {
        $this->init();

        $invoice = DB::table('invoices')->select(
            \DB::raw(
                'invoices.id, 
                invoices.date, 
                invoices.is_locked, 
                invoices.number, 
                invoices.amount_total, 
                invoices.details_self, 
                invoices.structuralunit_id, 
                currencies.name as currency_name, 
                partners.name as partnername,
                structuralunits.title as structuralunitname, 
                invoice_types.title as invoicetypename'
            )
        )
            ->leftJoin('partners', 'invoices.partner_id', '=', 'partners.id')
            ->leftJoin('structuralunits', 'invoices.structuralunit_id', '=', 'structuralunits.id')
            ->leftJoin('invoice_types', 'invoices.invoicetype_id', '=', 'invoice_types.id')
            ->leftJoin('currencies', 'invoices.currency_id', '=', 'currencies.id')
            ->where('invoices.company_id', $this->companyId);


        if ($this->partnerId) {
            $invoice = $invoice->where('invoices.partner_id', $this->partnerId);
        }

        if ($this->details) {
            $invoice = $invoice->where('invoices.details_self', 'LIKE', '%'.$this->details.'%');
        }

        if ($this->typeId) {
            $invoice = $invoice->where('invoices.invoicetype_id', $this->typeId);
        }

        if ($this->structId) {
            $invoice = $invoice->where('invoices.structuralunit_id', $this->structId);
        }

        if ($this->dateFrom) {
            try {
                $dateFrom = Carbon::createFromFormat('d.m.Y', $this->dateFrom)->startOfDay()->format('Y-m-d H:i:s');
                $invoice = $invoice->where('invoices.date', '>=', $dateFrom);

            } catch (\Exception $e) {

            }
        }

        if ($this->dateTo) {
            try {
                $dateTo = Carbon::createFromFormat('d.m.Y', $this->dateTo)->startOfDay()->format('Y-m-d H:i:s');
                $invoice = $invoice->where('invoices.date', '<=', $dateTo);

            } catch (\Exception $e) {

            }
        }

//        if ($this->dateTo) {
//            $invoice = $invoice->where('invoices.date', '=<',$this->dateTo);
//        }

        if (in_array($this->sortColumn, [
                'id',
                'amount_total',
                'date',
                'number',
                'partnername',
                'structuralunitname',
                'currency_name',
                'invoicetypename',
                'details_self',
            ]) && in_array($this->sortDirection, ['asc', 'desc'])) {
            $invoice = $invoice->orderBy($this->sortColumn, $this->sortDirection);

            if($this->sortColumn === 'date'){
                $invoice = $invoice->orderBy('invoices.created_at', $this->sortDirection);
            }
        }

        if (!Auth::user()->isAdmin() && $this->company->structuralunits->count() > 0) {
            $availableUnitsForUser = Auth::user()->structuralunits->where('company_id', $this->companyId)
                ->pluck('id')
                ->all();

            $invoice = $invoice->where(function ($q) use($availableUnitsForUser) {
                foreach ($availableUnitsForUser as $unit){
                    $q = $q->orWhere('invoices.structuralunit_id', $unit);
                }
            });
        }


        try {
            try {
                $invoice = $invoice->paginate(15);
            } catch (\Exception $e) {
//                dd($e->getMessage());
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // dd('try to sort none existing column!');
            return false;
        }

        return $invoice;

    }

    public function getPartners()
    {
        $this->init();

        return Partner::where('company_id', $this->companyId)->orderBy(
            'name', 'asc'
        )->get()->map(function ($item) {
            return (object)[
                'name' => $item->name,
                'id' => $item->id,
            ];
        });
    }

    public function getStructuralunits()
    {
        $this->init();

        $units = Structuralunit::where('company_id', $this->companyId)->orderBy('title', 'asc');

        if (!Auth::user()->isAdmin()) {
            $units = $units->whereHas('users', function ($q) {
                $q->where('users.id', Auth::user()->id);
            });
        }

        return $units->get()->map(function ($item) {
            return (object)[
                'id' => $item->id,
                'title' => $item->title,
            ];
        });
    }

    public function getInvoicetypes()
    {
        return InvoiceType::orderBy('title', 'asc')->get()->map(function ($item) {
            return (object)[
                'id' => $item->id,
                'title' => $item->title,
            ];
        });
    }

    public function create()
    {

    }
}
