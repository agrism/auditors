<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Employee;
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

    public function handle(Request $request)
    {

        if ($employeeId = $request->employee_id) {

            if ($empl = Employee::with(['employeeHistory' => function($h){
                $h->orderBy('date', 'asc');
            }])->where('id', $employeeId)->first()) {

                $this->activeEmployeeId = $empl->id;

                $accumulatedDays = 0;

                $currentMonthLastDate = null;

                foreach ($empl->employeeHistory as $item) {

                    if(!$currentMonthLastDate){
                        $currentMonthLastDate = Carbon::parse($item->date)->lastOfMonth();
                    }


                    $currentDate = Carbon::parse($item->date);


                    $buff = $this->bufferFactory(round($accumulatedDays,2));

                    while($currentMonthLastDate->lessThan($currentDate)){


                        $buff = $this->bufferFactory($accumulatedDays);

                        $buff['earnedDate'] = $currentMonthLastDate->format('Y-m-d');
                        $buff['earnedDays'] = 1.67;
                        $accumulatedDays += 1.67;
                        $buff['accumulatedDays'] = $accumulatedDays;

                        $this->pushData($buff);

                        $currentMonthLastDate->addDay()->lastOfMonth();
                    }

                    $buff = $this->bufferFactory(round($accumulatedDays,2));


                    switch ($item->type) {
                        case EmployeeHistory::TYPE_USED_VACATION:
                            {
                                $buff['usedDate'] = $item->date;
                                $buff['usedDays'] = $item->days;
                                $accumulatedDays -= $item->days;
                                break;
                            };
                        case EmployeeHistory::TYPE_ACCUMULATED_VACATION:
                            {
                                $buff['earnedDate'] = $item->date;
                                $buff['earnedDays'] = $item->days;
                                $accumulatedDays += $item->days;
                                break;
                            };
                    }


                    $buff['accumulatedDays'] = round($accumulatedDays,2);

                    $this->pushData($buff);
                }
            }

            return $this->index($request);
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

                    die;
                }

                foreach ($fileEmployee->vacation_working_days as $vacationWorkingDayInFile) {

                    if ($vacationWorkingDayInFile !== Carbon::parse($vacationWorkingDayInFile)->format('Y-m-d')) {
                        dd('Date is not in correct format, value: ' . $vacationWorkingDayInFile);
                        continue;
                    }


                    if (EmployeeHistory::where('date', $vacationWorkingDayInFile)->where('employee_id', $dbEmployee->id)->where('company_id', $dbCompany->id)->first()) {
                        continue;
                    }

                    $e = new EmployeeHistory;
                    $e->company_id = $dbCompany->id;
                    $e->date = $vacationWorkingDayInFile;
                    $e->employee_id = $dbEmployee->id;
                    $e->days = 1;
                    $e->type = EmployeeHistory::TYPE_USED_VACATION;
                    $e->save();
                }
            }
        }

        return $this->index($request)->with('success', 'true')->with('form_message', 'done');

    }


    public function index(Request $request)
    {
        $data = $this->data;

        $employees = Employee::where('company_id', $this->selectedCompanyId)->get();

        $employees = $employees->prepend(new Employee)
            ->map(function ($record) {

                $record->active = $record->id == $this->activeEmployeeId;

                return $record;
            });

        return view('admin.vacations.index', compact('data', 'employees'));
    }

    public function bufferFactory(float $accumulatedDays = 0.00): array
    {
        return [
            'usedDate' => null,
            'usedDays' => null,
            'earnedDate' => null,
            'earnedDays' => null,
            'accumulatedDays' => $accumulatedDays,
        ];
    }

    public function pushData(array $data): self
    {


        $this->data['items'][] = (object)$data;
        $this->data['totalUsed'] -= $data['usedDays'];
        $this->data['totalEarned'] += $data['earnedDays'];
        return $this;
    }

    public function addEarned(){

    }

}