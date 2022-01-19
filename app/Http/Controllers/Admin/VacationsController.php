<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Calendar;
use App\Employee;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\EmployeeHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class VacationsController extends Controller
{
    private $data = [];

    private $activeEmployeeId;

    private $selectedCompanyId = 22;

    public function handle(Request $request)
    {
        $data                   = $request->get('data');
        $this->activeEmployeeId = $request->get('employee_id');

        $data = preg_split('/\n|\r\n?/',
            $data);

        $newData = [];

        $daysTotal        = 0;
        $workingDaysTotal = 0;
        $holidaysTotal    = 0;

        $data = array_filter($data);

        foreach ($data as $period) {

            if ( !$period) {
                continue;
            }

            $buffer = preg_split('/\t/',
                $period);

            if (empty($buffer[0]) || empty($buffer[1])) {
                continue;
            }

            $from = $buffer[0];
            $to   = $buffer[1];

            $days        = 0;
            $workingDays = 0;
            $holidays    = 0;

            foreach (
                CarbonPeriod::create($from,
                    $to) as $dateObject
            ) {

                $days++;

                if (
                $currentDay = Calendar::where('date',
                    $dateObject->format('Y-m-d'))
                    ->first()
                ) {
                    if ($currentDay->type !== 'regular') {
                        $holidays++;
                        continue;
                    }
                }

                if ($dateObject->isWeekend()) {
                    $holidays++;
                    continue;
                }

                $workingDays++;

                if ( !$this->selectedCompanyId || !$this->activeEmployeeId) {
                    continue;
                }


                if ( !$vacationDate = EmployeeHistory::where('company_id',
                    $this->companyId)
                    ->where('employee_id',
                        $this->activeEmployeeId)
                    ->where('type', EmployeeHistory::TYPE_USED_VACATION)
                    ->where('date',
                        $dateObject->format('Y-m-d'))
                    ->first()
                ) {
                    EmployeeHistory::insert([
                        'date'        => $dateObject->format('Y-m-d'),
                        'company_id'  => $this->selectedCompanyId,
                        'employee_id' => $this->activeEmployeeId,
                        'type'        => EmployeeHistory::TYPE_USED_VACATION,
                        'days'   => 1,
                        'created_at'  => now(),
                        'updated_at'  => now(),
                    ]);
                }


            }

            $workingDaysTotal += $workingDays;
            $holidaysTotal    += $holidays;
            $daysTotal        += $days;


            $newData[date('m.Y',
                strtotime($buffer[0]))][] = [
                'from'         => $from,
                'to'           => $to,
                'days'         => $days,
                'holidays'     => $holidays,
                'working-days' => $workingDays,
            ];
        }

        $first = array_key_first($newData);
        $last  = array_key_last($newData);

        try {

            foreach (
                CarbonPeriod::create(Carbon::createFromFormat('m.Y',
                    $first),
                    Carbon::createFromFormat('m.Y',
                        $last)) as $per
            ) {
                $newPer = $per->format('m.Y');

                if ( !isset($newData[$newPer])) {
                    $newData[$newPer][] = [
                        'from'         => '-',
                        'to'           => '-',
                        'days'         => 0,
                        'holidays'     => 0,
                        'working-days' => 0,
                    ];
                }
            }

        } catch (\Exception $exception) {

        }

        uksort($newData,
            function (
                $a,
                $b
            ) {

                $a = Carbon::createFromFormat('m.Y',
                    $a);
                $b = Carbon::createFromFormat('m.Y',
                    $b);

                return $a->greaterThan($b);
            });

        $newData['total'] = [
            'from'         => '-',
            'to'           => '-',
            'days'         => $daysTotal,
            'holidays'     => $holidaysTotal,
            'working-days' => $workingDaysTotal,
        ];

        $this->data = $newData;

        return $this->index($request);

    }

    public function index(Request $request)
    {
        $data = $this->data;

        $employees = Employee::where('company_id',
            $this->selectedCompanyId)
            ->get();

        $employees = $employees->prepend(new Employee)
            ->map(function ($record)
            {

                $record->active = $record->id == $this->activeEmployeeId;

                return $record;
            });

        return view('admin.vacations.index',
            compact('data',
                'employees'));
    }

}