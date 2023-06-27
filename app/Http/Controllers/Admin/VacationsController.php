<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Employee;
use App\Services\VacationService;
use Carbon\Carbon;
use App\EmployeeHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class VacationsController extends Controller
{
    private $data = [
        'items' => [],
        'totalEarned' => 0,
        'totalUsed' => 0,
    ];

    private array $colorMap = [
        'used' => [
            'color' => 'black',
            'bgColor' => 'yellow',
        ],
        'earned' => [
            'color' => 'white',
            'bgColor' => 'green',
        ],
        'start' => [
            'color' => 'white',
            'bgColor' => 'red',
        ],
        'end' => [
            'color' => 'white',
            'bgColor' => 'brown',
        ],
    ];

    private $activeEmployeeId;

    private $selectedCompanyId = 22;

    protected $companyMap = [
        'avatermo' => 'AVA TERMO, SIA',
        'zsapini' => 'ZSApini,SIA',
        'inno' => 'InnoBalticum, SIA',
        '3ro' => '3ro, SIA',
        'ambros' => 'Ambross, SIA',
        'egomedia' => 'Ego Media, SIA',
        'aike' => 'Aike Trading, SIA',
        'Paragrafs' => 'Paragrafs, SIA',
        'anprojekcti' => 'AN Projekti, SIA',
        'kezberi' => 'Ķezberi, SIA',
    ];

    protected $ignoreCompanies = [
        'Grohmann',
        'onetrading',
        'LAWANDFIN',
    ];

    protected $ignoreEmloyees = [
        'test1 test',
        'test2 test',
        'test test',
    ];

    protected $availableEventTypes = [
        EmployeeHistory::TYPE_EARNED_VACATION,
        EmployeeHistory::TYPE_USED_VACATION,
        EmployeeHistory::TYPE_START,
        EmployeeHistory::TYPE_END,
    ];

    public function __construct(Request $request)
    {
        $this->selectedCompanyId = $request->get('company_id');
    }

    public function handle(Request $request)
    {
        if ($employeeId = $request->employee_id) {
            $this->activeEmployeeId = $employeeId;

            if ($request->recalculate_selected_employee === 'Recalculate selected employee') {
                $this->recalculateDataForSelectedEmployee($employeeId);
            }

            if($this->selectedCompanyId && $request->recalculate_for_all_company == 'Recalculate selected company all employees'){
                $this->recalculateDataForSelectedCompany($this->selectedCompanyId);
            }

            if($this->selectedCompanyId && $request->employee_id && $request->form_event_date && $request->form_event_days && $request->form_event_type){
                $this->registerHistoryEvent(
                    $this->selectedCompanyId,
                    $request->employee_id,
                    $request->form_event_date,
                    $request->form_event_type,
                    $request->form_event_days,
                    $request->form_event_description
                );
            }
        }

        if ($employeeId = $request->employee_id) {

            if ($idToDelete = $request->get('delete_history_event_id')) {
                EmployeeHistory::where('company_id', $this->selectedCompanyId)->where('employee_id', $employeeId)->whereId($idToDelete)->delete();
            }

            $this->activeEmployeeId = $employeeId;

            $this->data = VacationService::factory()->registerCompany($this->selectedCompanyId)->registerEmployee($employeeId)->getData();

            if (!$request->file('data')) {
                return $this->index($request);
            }
        }

        if ($request->newEmployeeName && $request->newEmployeeNameCompanyId && $request->newEmployeeName) {

            if (Employee::query()->where('name', $request->newEmployeeName)->where('company_id', $request->newEmployeeNameCompanyId)->first()) {
                dd('Employee exists already: ' . $request->newEmployeeName);
            }

            $newEmployee = new Employee;
            $newEmployee->company_id = $request->newEmployeeNameCompanyId;
            $newEmployee->name = trim($request->newEmployeeName);
            $newEmployee->registration_number = '-';
            $newEmployee->save();
        }

        if (!$file = $request->get('jsonData')) {
            if (!$file = $request->file('data')) {
                return $this->index($request);
            }
            $file = $file->get();
        }

        $file = json_decode($file);

        foreach ($file as $fileCompany) {

            if (in_array($fileCompany->title, $this->ignoreCompanies)) {
                continue;
            }

            if (!isset($this->companyMap[$fileCompany->title])) {

                dump('missing map for company : ' . $fileCompany->title);
                continue;
            }

            if (!$dbCompany = Company::query()->where('title', $this->companyMap[$fileCompany->title])->first()) {
                dump('missing mapped value in db, value: ' . $this->companyMap[$fileCompany->title]);
                continue;
            }


            foreach ($fileCompany->employees as $fileEmployee) {

                $fullNameStartingSurname = sprintf('%s %s', trim($fileEmployee->surname), trim($fileEmployee->name));

                $fullNameStartingSurname = preg_replace('/[,0-9]/', '', $fullNameStartingSurname);
                $fullNameStartingSurname = trim($fullNameStartingSurname);

                if (in_array($fullNameStartingSurname, $this->ignoreEmloyees)) {
                    continue;
                }

                if (!$dbEmployee = $dbCompany->employees->where('name', $fullNameStartingSurname)->where('company_id', $dbCompany->id)->first()) {
                    dump('Employee not found in db, employee: ' . $fullNameStartingSurname);
                    dump('existing customers', $dbCompany->employees->pluck('name')->toArray());

                    $csrfToken = csrf_token();
                    $route = route('admin.vacations.handle');
                    $jsonData = json_encode($file);

                    $form = <<<HTML
                <form action="$route" method="post"  enctype="multipart/form-data" >
                    <input type="hidden" name="_token" value="$csrfToken" >
                    <input type="text" name="newEmployeeNameCompanyId" value="{$dbCompany->id}" >
                    <input type="text" name="newEmployeeName" value="{$fullNameStartingSurname}" class="form-control">
                    <input type="hidden" name="jsonData" class="form-control" value='{$jsonData}'>
                    <input type="submit" value="create customer">
                </form>
HTML;

                    echo $form;

                    dd('die1');
                }

                // insert start working date
                foreach ($fileEmployee->employeeHistory as $eventDate => $eventCode) {
                    if ($eventCode == 11) {
                        $this->registerHistoryEventIfNotRegistered($dbCompany->id, $dbEmployee->id, $eventDate, EmployeeHistory::TYPE_START, 0, 'jumis');
                    } elseif (in_array($eventCode, [25, 21])) {
                        $this->registerHistoryEventIfNotRegistered($dbCompany->id, $dbEmployee->id, $eventDate, EmployeeHistory::TYPE_END, 0, 'jumis');
                    }
                }

                // insert used vacation working days
                foreach ($fileEmployee->vacation_working_days as $vacationWorkingDayInFile) {
                    $this->registerHistoryEventIfNotRegistered($dbCompany->id, $dbEmployee->id, $vacationWorkingDayInFile, EmployeeHistory::TYPE_USED_VACATION, 1, 'jumis');
                }
            }
        }

        return $this->index($request)->with('success', 'true')->with('form_message', 'done');
    }

    public function index(Request $request)
    {
        $data = $this->data;

        $this->selectedCompanyId = $request->get('company_id');

        $companies = Company::get()->map(function ($company) {
            $company->active = $company->id == $this->selectedCompanyId;
            return $company;
        });

        $employees = Employee::where('company_id', $this->selectedCompanyId)->get();

        $employees = $employees->prepend(new Employee)
            ->map(function ($record) {

                $record->active = $record->id == $this->activeEmployeeId;

                return $record;
            });

        $colorMap = VacationService::$colorMap;

        $eventTypes = $this->availableEventTypes;

        return view('admin.vacations.index', compact('data','colorMap', 'employees', 'companies', 'eventTypes'));
    }

    private function recalculateDataForSelectedCompany($companyId): self
    {
        Employee::where('company_id', $companyId)->get()->each(function($employee){
            $this->recalculateDataForSelectedEmployee($employee->id);
        });

        return $this;
    }

    private function recalculateDataForSelectedEmployee($employeeId): self
    {
        if (!Employee::where('company_id', $this->selectedCompanyId)->where('id', $employeeId)->first()) {
            dump('Employee not found, id: ' . $employeeId . ', for company: ' . $this->selectedCompanyId);
        }

        $startEndDates = [];

        EmployeeHistory::where('company_id', $this->selectedCompanyId)
            ->where('employee_id', $employeeId)
            ->orderBy('date', 'asc')
            ->get()
            ->each(function ($historyRecord) use (&$startEndDates) {

                if ($historyRecord->type == EmployeeHistory::TYPE_START) {
                    $startEndDates[] = $historyRecord->date;
                }

                if ($historyRecord->type == EmployeeHistory::TYPE_END) {
                    $startEndDates[] = $historyRecord->date;
                }
            });

        $started = false;
        $ended = false;

        $startEndDates[] = Carbon::now()->subMonth()->lastOfMonth()->format('Y-m-d');

        foreach ($startEndDates as $index => $date) {
            if (!$ended && !$started) {
                $started = true;
                $earnedDays = $this->calculateEarnedDaysTillEndOfMonth($date);
                $this->registerHistoryEventIfNotRegistered(
                    $this->selectedCompanyId,
                    $employeeId,
                    $date,
                    EmployeeHistory::TYPE_EARNED_VACATION,
                    $earnedDays
                );

                if ($nextDate = ($startEndDates[$index + 1] ?? null)) {
                    $this->calculateAndRegisterEarnedDaysTillDateNotIncluded(
                        $employeeId, Carbon::parse($date)->lastOfMonth()->format('Y-m-d'),
                        $nextDate
                    );
                }

                continue;
            }

            if (!$ended && $started) {

                $ended = true;
                $started = false;
                $earnedDays = $this->calculateEarnedDaysFromStartOfMonth($date);
                $this->registerHistoryEventIfNotRegistered(
                    $this->selectedCompanyId,
                    $employeeId,
                    $date,
                    EmployeeHistory::TYPE_EARNED_VACATION,
                    $earnedDays
                );
                continue;
            }

            if (!$started && $ended) {
                $ended = false;
                $started = true;
                $earnedDays = $this->calculateEarnedDaysTillEndOfMonth($date);

                if( !(($startEndDates[$index + 1] ?? null)) && $earnedDays < 0.1){
                    continue;
                }

                $this->registerHistoryEventIfNotRegistered(
                    $this->selectedCompanyId,
                    $employeeId, Carbon::parse($date)->lastOfMonth()->format('Y-m-d'),
                    EmployeeHistory::TYPE_EARNED_VACATION,
                    $earnedDays
                );

                if ($nextDate = ($startEndDates[$index + 1] ?? null)) {
                    $this->calculateAndRegisterEarnedDaysTillDateNotIncluded($employeeId, $date, $nextDate);
                }
                continue;
            }

        }

        return $this;
    }

    private function calculateAndRegisterEarnedDaysTillDateNotIncluded($employeeId, $from, $till): self
    {
        $till = Carbon::parse($till)->firstOfMonth()->subMonth()->lastOfMonth();
        $from = Carbon::parse($from)->firstOfMonth()->addMonth();

        while ($from->lessThanOrEqualTo($till)) {

            $date = Carbon::parse($from->format('Y-m-d'))->lastOfMonth()->format('Y-m-d');

            $this->registerHistoryEventIfNotRegistered($this->selectedCompanyId, $employeeId, $date, EmployeeHistory::TYPE_EARNED_VACATION, 1.67);

            $from->startOfMonth()->addMonth()->lastOfMonth();
        }

        return $this;
    }

    private function calculateEarnedDaysTillEndOfMonth($date): float
    {
        $day = Carbon::parse($date)->format('d');
        $daysInMonth = Carbon::parse($date)->lastOfMonth()->format('d');
        $daysWorked = $daysInMonth - $day;
        return round(1.67 * $daysWorked / $daysInMonth, 2);
    }

    private function calculateEarnedDaysFromStartOfMonth($date): float
    {
        $day = Carbon::parse($date)->format('d');
        $daysInMonth = Carbon::parse($date)->lastOfMonth()->format('d');
        $daysWorked = $day;
        return round(1.67 * $daysWorked / $daysInMonth, 2);
    }

    private function registerHistoryEventIfNotRegistered($companyId, $employeeId, $eventDate, $type, $days = 0, $description = ''): self
    {
        $this->validateDate($eventDate);

        if (EmployeeHistory::where('date', $eventDate)
            ->where('employee_id', $employeeId)
            ->where('company_id', $companyId)
            ->where('type', $type)
            ->first()) {
            return $this;
        }

        $this->registerHistoryEvent($companyId, $employeeId, $eventDate, $type, $days, $description);


        return $this;
    }

    private function registerHistoryEvent(string $companyId, string $employeeId, string $eventDate, string $type, float $days = 0, string $description = ''): self
    {
        $this->validateDate($eventDate)->validateEventType($type)->validateEmployeeCompany($employeeId, $companyId);

        $e = new EmployeeHistory;
        $e->company_id = $companyId;
        $e->date = $eventDate;
        $e->employee_id = $employeeId;
        $e->days = ROUND($days, 2);
        $e->type = $type;
        $e->description = $description;
        $e->save();

        return $this;
    }

    protected function validateDate(string $date): self
    {
        if ($date !== Carbon::parse($date)->format('Y-m-d')) {
            dd('Date is not in correct format, value: ' . $date);
        }

        return $this;
    }

    protected function validateEventType(string $type): self
    {
        if (!in_array($type, $this->availableEventTypes)) {
            dd('Type is not in correct, value: "' . $type. '", alloved types: '.json_encode($this->availableEventTypes));
        }

        return $this;
    }

    protected function validateEmployeeCompany(string $employeeId, string $companyId): self
    {
        if (!Employee::query()->where('company_id', $companyId)->where('id', $employeeId)->first()) {
            dd('EmployeeId ('.$companyId.') does not have companyId '.$companyId);
        }

        return $this;
    }
}