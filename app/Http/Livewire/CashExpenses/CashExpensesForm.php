<?php


namespace App\Http\Livewire\CashExpenses;

use App\Partner;
use Carbon\Carbon;
use Livewire\Component;
use App\Services\AuthUser;
use App\Services\VatCalculator;
use Illuminate\Support\Facades\DB;

class CashExpensesForm extends Component
{
    public static $get;
    public        $cashExpenseId;

    public $cashExpense = [
        'employee_id' => null,
        'no'          => null,
        'date'        => null,
        'totalAmount' => 0.00,
    ];

    public $vatCalc = [];

    public $accounts    = [];
    public $lines       = [];
    public $expenseLine = [
        'id'                  => null,
        'no'                  => null,
        'document_no'         => null,
        'date'                => null,
        'partner_id'          => null,
        'amount_with_vat'     => null,
        'amount_without_vat'  => null,
        'amount_vat'          => null,
        'vat_calculator_name' => null,
        'budget_id'           => null,
        'account_id'          => null,
        'description'         => null,
    ];

    protected $listeners = [
        'updatedSelectedId'         => 'updateSelectedPartnerId',
        'updatedSelectedEmployeeId' => 'updatedSelectedEmployeeId',
        'updatedSelectedBudgetId'   => 'updatedSelectedBudgetId',
        'updatedSelectedAccountId'  => 'updatedSelectedAccountId',
    ];
    private   $company;
    private   $companyId;

    public function __construct($id = null)
    {
        parent::__construct($id);

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

    public function render()
    {
        return view('livewire.cash-expenses.cash-expenses-form',
            []);
    }

    public function close()
    {
        $this->emitUp('closeForm');
    }

    public function openDelete($id)
    {
        $this->expenseLine['id'] = $id;
        $this->dispatchBrowserEvent('openModal_expense_line_delete');
    }

    public function expenseLineDeleteConfirm()
    {
        DB::table('cash_expense_lines')
            ->where('id',
                $this->expenseLine['id'])
            ->where('company_id',
                $this->companyId)
            ->delete();

        $this->dispatchBrowserEvent('closeModal_expense_line_delete');

    }

    public function expenseLineDeleteCancel()
    {
        $this->dispatchBrowserEvent('closeModal_expense_line_delete');
    }


    public function openEdit($id)
    {
        $this->expenseLine['id'] = $id;

        if ( !$expenseLine = DB::table('cash_expense_lines')
            ->where('company_id',
                $this->companyId)
            ->where('id',
                $id)
            ->first()
        ) {
            $this->clearLine();
            $this->dispatchBrowserEvent('openModal_expense_line');

            return;
        }

        $date = $expenseLine->date
            ? Carbon::createFromFormat('Y-m-d',
                $expenseLine->date)
                ->format('d.m.Y')
            : date('d.m.Y');

        $this->expenseLine = [
            'id'                  => $expenseLine->id,
            'no'                  => $expenseLine->no,
            'document_no'         => $expenseLine->document_no,
            'date'                => $date,
            'amount_with_vat'     => $expenseLine->amount_with_vat,
            'amount_vat'          => $expenseLine->amount_vat,
            'amount_without_vat'  => $expenseLine->amount_without_vat,
            'partner_id'          => $expenseLine->partner_id,
            'budget_id'           => $expenseLine->budget_id,
            'account_id'          => $expenseLine->account_id,
            'vat_calculator_name' => $expenseLine->vat_calculator_name,
            'description'         => $expenseLine->description,
        ];

        $this->dispatchBrowserEvent('openModal_expense_line');

    }

    public function clearLine()
    {
        $this->expenseLine = [
            'id'                  => null,
            'no'                  => null,
            'document_no'         => null,
            'date'                => null,
            'partner_id'          => null,
            'amount_with_vat'     => null,
            'amount_without_vat'  => null,
            'amount_vat'          => null,
            'budget_id'           => null,
            'account_id'          => null,
            'vat_calculator_name' => null,
            'description'         => null,
        ];
    }

    public function updatedCashExpenseDate()
    {
        $this->validate([
            'cashExpense.date' => 'required',
        ]);

        $this->_update();
    }

    private function _update() : self
    {
        try {
            $date = Carbon::createFromFormat('d.m.Y',
                $this->cashExpense['date'])
                ->format('Y-m-d');
        } catch (\Exception $e) {
            $date = date('Y-m-d');
        }


        DB::table('cash_expenses')
            ->where('id',
                $this->getCashExpenseId())
            ->where('company_id',
                $this->companyId)
            ->update([
                'date'        => $date,
                'employee_id' => $this->cashExpense['employee_id'] ?? null,
                'no'          => $this->cashExpense['no'] ?? null,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);

        $this->clear()
            ->get();

        return $this;
    }

    public function getCashExpenseId()
    {
        if ( !$this->cashExpenseId) {
            DB::table('cash_expenses')
                ->insert([
                    'company_id'  => $this->companyId,
                    'date'        => date('Y-m-d'),
                    'currency_id' => 1,
                    'created_at'  => date('Y-m-d H:i:s'),
                ]);

            if (
            $new = DB::table('cash_expenses')
                ->where('company_id',
                    $this->companyId)
                ->orderBy('created_at',
                    'desc')
                ->first()
            ) {
                $this->cashExpenseId = $new->id;
            }
        }

        return $this->cashExpenseId;

    }

    public function get()
    {
        if ( !$this->cashExpenseId) {
            return;
        }

        if ( !self::$get) {

            $this->clear();

            self::$get = DB::table('cash_expenses as ce')
                ->select([
                    'ce.id',
                    'ce.no',
                    'ce.account_id',
                    'ce.date',
                    'ce.employee_id',
                    'empl.name',
                ])
                ->leftJoin('employees as empl',
                    'ce.employee_id',
                    '=',
                    'empl.id')
                ->where('ce.company_id',
                    $this->companyId)
                ->where('ce.id',
                    $this->getCashExpenseId())
                ->first();

            if (self::$get) {
                self::$get->date                  = Carbon::createFromFormat('Y-m-d',
                    self::$get->date)
                    ->format('d.m.Y');
                $this->cashExpense['date']        = self::$get->date;
                $this->cashExpense['no']          = self::$get->no;
                $this->cashExpense['employee_id'] = self::$get->employee_id;
            }
        }

        if ( !empty(self::$get->id)) {

            $this->cashExpense['totalAmount'] = 0.00;

            $lines = DB::table('cash_expense_lines as cel')
                ->select([
                    'cel.id',
                    'cel.no',
                    'cel.document_no',
                    'cel.date',
                    'cel.description',
                    'cel.amount_with_vat',
                    'cel.amount_vat',
                    'cel.amount_without_vat',
                    'cel.budget_id',
                    'cel.account_id',
                    'cel.description',
                    'cel.document_no',
                    'cel.vat_calculator_name',
                    'p.name as partner_name',
                    'p.vat_number as partner_vat_number',
                    'b.code as budget_code',
                    'a.code as account_code',
                ])
                ->leftJoin('partners as p',
                    'cel.partner_id',
                    '=',
                    'p.id')
                ->leftJoin('budgets as b',
                    'cel.budget_id',
                    '=',
                    'b.id')
                ->leftJoin('accounts as a',
                    'cel.account_id',
                    '=',
                    'a.id')
                ->where('cel.cash_expenses_id',
                    self::$get->id)
                ->where('cel.company_id',
                    $this->companyId)
                ->orderBy('cel.no',
                    'asc')
                ->get()
                ->map(function ($record)
                {
                    $record->date               = Carbon::parse($record->date)
                        ->format('d.m.Y');
                    $record->amount_with_vat    = number_format($record->amount_with_vat,
                        2);
                    $record->amount_without_vat = number_format($record->amount_without_vat,
                        2);
                    $record->amount_vat         = number_format($record->amount_vat,
                        2);

                    $record->description = $this->limitString(strval($record->description),
                        15);

                    $record->partner_name = $this->limitString(strval($record->partner_name),
                        15);


                    // dd($record->partner_name);
                    $record->budget_code = $this->limitString(strval($record->budget_code),
                        15);

                    return $record;
                })
                ->toArray();
        }

        if ( !self::$get) {
            self::$get = (object) ['lines'];
        }

        self::$get->lines = $lines ?? [];

        return self::$get;
    }

    public function clear() : self
    {
        $this->cashExpense = [
            'no'          => null,
            'date'        => null,
            'employee_id' => null,
            'totalAmount' => 0.00,
        ];

        self::$get = null;

        return $this;
    }

    private function limitString(
        string $string,
        int $length = 20
    ) : string {
        $postFix = strlen($string) > $length
            ? '..'
            : '';

        return trim(mb_substr($string,
                0,
                $length)) . $postFix;
    }

    public function updatedCashExpenseNo()
    {
        $this->validate([
            'cashExpense.no' => 'required',
        ]);

        $this->_update();
    }

    public function updated(
        $name,
        $value
    ) {

        if (
        in_array($name,
            [
                'expenseLine.vat_calculator_name',
                'expenseLine.amount_with_vat',
            ])
        ) {
            $cal = VatCalculator::factory(floatval($this->expenseLine['amount_with_vat']),
                strval($this->expenseLine['vat_calculator_name']))
                ->calculate();

            $this->expenseLine['amount_without_vat'] = $cal->getAmountWithOutVat();
            $this->expenseLine['amount_vat']         = $cal->getVat();
        }

        // dd($name);
        // $this->validate([
        //     'cashExpense.employee_id' => 'required',
        // ]);

        $this->_update();
    }

    public function mount()
    {
        $this->accounts = DB::table('accounts')
            ->where('company_id',
                $this->companyId)
            ->get()
            ->pluck('code',
                'id');

        $this->get();
    }

    public function expenseLineOpen()
    {
        $this->clearLine();

        $this->get();

        $countPlusOne = count(self::$get->lines) + 1;
        $nextNo       = 0;
        collect(self::$get->lines ?? [])->each(function ($record) use
        (
            &
            $nextNo
        )
        {
            if ($nextNo < $record->no) {
                $nextNo = $record->no;
            }
        });
        $nextNo++;

        $this->expenseLine['no']   = $nextNo > $countPlusOne
            ? $nextNo
            : $countPlusOne;
        $this->expenseLine['date'] = date('d.m.Y');

        $this->dispatchBrowserEvent('openModal_expense_line');
    }

    public function expenseLineConfirm()
    {
        $notIn = [];
        foreach ($this->get()->lines ?? [] as $line) {
            if ($line->id === $this->expenseLine['id']) {
                continue;
            }
            $notIn[] = $line->no;
        }

        $this->validate([
            'expenseLine.no'   => 'required|not_in:' . implode(',',
                    $notIn),
            'expenseLine.date' => 'required',
        ]);


        if ($this->expenseLine['vat_calculator_name'] !== null && $this->expenseLine['vat_calculator_name'] != VatCalculator::NO) {

            if ($this->expenseLine['amount_without_vat'] >= 150) {
                $this->validate([
                    'expenseLine.document_no' => 'required',
                ],
                    [
                        'required' => 'Doc No is required',
                    ]);
            }


            if ($this->expenseLine['partner_id']) {
                if (
                $partner = Partner::where('company_id',
                    $this->companyId)
                    ->where('id',
                        $this->expenseLine['partner_id'])
                    ->where(function($q){
                        $q->whereNull('vat_number')->orWhereRaw(DB::raw('length(vat_number) < 10'));
                    })
                    ->first()
                ) {
                    $this->validate([
                        'expenseLine.partner_id' => [
                            function (
                                $attribute,
                                $value,
                                $fail
                            ) {
                                $fail('Partner does not have VAT no.');
                            },
                        ],
                    ]);

                }
            }
        }


        try {
            $date = Carbon::createFromFormat('d.m.Y',
                $this->expenseLine['date'])
                ->format('Y-m-d');
        } catch (\Exception $e) {
            $date = date('Y-m-d');
        }


        $calc = VatCalculator::factory($this->expenseLine['amount_with_vat'] ?? 0.00,
            $this->expenseLine['vat_calculator_name'] ?? '')
            ->calculate();

        if ($this->expenseLine['id']) {
            DB::table('cash_expense_lines')
                ->where('company_id',
                    $this->companyId)
                ->where('cash_expenses_id',
                    $this->getCashExpenseId())
                ->where('id',
                    $this->expenseLine['id'])
                ->update([
                    'no'                  => intval($this->expenseLine['no']),
                    'date'                => $date,
                    'partner_id'          => $this->expenseLine['partner_id'],
                    'document_no'         => $this->expenseLine['document_no'],
                    'amount_with_vat'     => $this->expenseLine['amount_with_vat'],
                    'budget_id'           => $this->expenseLine['budget_id'],
                    'account_id'          => $this->expenseLine['account_id'],
                    'description'         => $this->expenseLine['description'],
                    'amount_vat'          => $calc->getVat(),
                    'amount_without_vat'  => $calc->getAmountWithOutVat(),
                    'vat_calculator_name' => $this->expenseLine['vat_calculator_name'],
                    'updated_at'          => date('Y-m-d H:i:s'),
                    'vat_calculation'     => $calc->export(),
                ]);
        } else {
            DB::table('cash_expense_lines')
                ->insert([
                    'no'                  => intval($this->expenseLine['no']),
                    'document_no'         => $this->expenseLine['document_no'],
                    'date'                => $date,
                    'company_id'          => $this->companyId,
                    'cash_expenses_id'    => $this->getCashExpenseId(),
                    'partner_id'          => $this->expenseLine['partner_id'],
                    'amount_with_vat'     => $this->expenseLine['amount_with_vat'],
                    'budget_id'           => $this->expenseLine['budget_id'],
                    'account_id'          => $this->expenseLine['account_id'],
                    'description'         => $this->expenseLine['description'],
                    'amount_vat'          => $calc->getVat(),
                    'amount_without_vat'  => $calc->getAmountWithOutVat(),
                    'vat_calculator_name' => $this->expenseLine['vat_calculator_name'],
                    'created_at'          => date('Y-m-d H:i:s'),
                    'updated_at'          => date('Y-m-d H:i:s'),
                    'vat_calculation'     => $calc->export(),
                ]);
        }

        $this->clearLine();


        $this->dispatchBrowserEvent('closeModal_expense_line');
    }

    public function expenseLineCancel()
    {
        $this->clearLine();
        $this->dispatchBrowserEvent('closeModal_expense_line');
    }

    public function updateSelectedPartnerId($partnerId)
    {
        $this->expenseLine['partner_id'] = $partnerId;
    }

    public function updatedSelectedBudgetId($budgetId)
    {
        $this->expenseLine['budget_id'] = $budgetId;
    }

    public function updatedSelectedAccountId($accountId)
    {
        $this->expenseLine['account_id'] = $accountId;
    }

    public function updatedSelectedEmployeeId($employeeId)
    {
        $this->cashExpense['employee_id'] = $employeeId;

        $this->_update();
    }
}