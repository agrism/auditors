<?php

namespace App\Services;

use App\Company;
use App\Employee;
use App\EmployeeHistory;
use Carbon\Carbon;

class VacationService
{
    public static array $colorMap = [
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

    protected $companyId = null;
    protected $employeeId = null;
    private $data = [
        'items' => [],
        'totalEarned' => 0,
        'totalUsed' => 0,
    ];

    public static function factory(): self
    {
        return new static;
    }

    public function registerCompany(string $companyId): self
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function registerEmployee(string $employeeId): self
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    public function getData(): array
    {
        if ($empl = Employee::with(['employeeHistory' => function ($h) {
            $h->orderBy('date', 'asc');
        }])->where('id', $this->employeeId)->first()) {

            $accumulatedDays = 0;

            $currentMonthLastDate = null;

            foreach ($empl->employeeHistory as $item) {

                if (!$currentMonthLastDate) {
                    $currentMonthLastDate = Carbon::parse($item->date)->lastOfMonth();
                }

                $buff = $this->bufferFactory();

                $buff['id'] = $item->id;
                $buff['date'] = $item->date;

                if($item->type == EmployeeHistory::TYPE_USED_VACATION){
                    $accumulatedDays -= $item->days;
                    $buff['usedDays'] = -$item->days;
                    $buff['desc'] = 'used';
                }elseif ($item->type == EmployeeHistory::TYPE_EARNED_VACATION){
                    $accumulatedDays += $item->days;
                    $buff['earnedDays'] = $item->days;
                    $buff['desc'] = 'earned';
                }elseif ($item->type == EmployeeHistory::TYPE_START){
                    $buff['desc'] = 'start';
                }elseif ($item->type == EmployeeHistory::TYPE_END){
                    $buff['desc'] = 'end';
                }

                $buff['accumulatedDays'] = round($accumulatedDays, 2);
                $buff['description'] = $item->description;


                $this->pushData($buff);
            }
        }

        return $this->data;
    }

    public function getSummaryPerCompany(): array
    {
        $data = [];

        if($company = Company::query()->with(['employees.employeeHistory' => function($q){
            $q->orderBy('date', 'asc');
        }])->where('id', $this->companyId)->first()){
            foreach ($company->employees as $employee){

                $buffer = [];

                $employeeVacationBalance = 0;

                $isEmployeeActive = false;

                foreach ($employee->employeeHistory as $history){

                    if($history->type == EmployeeHistory::TYPE_START){
                        $isEmployeeActive = true;
                    }elseif($history->type == EmployeeHistory::TYPE_END){
                        $isEmployeeActive = false;
                    }

                    $multiplyer = $history->type == EmployeeHistory::TYPE_USED_VACATION ? -1 : 1;

                    $employeeVacationBalance += $history->days * $multiplyer;
                }

                if(!$isEmployeeActive){
                    continue;
                }

                $data[] = [
                    'employeeName' => $employee->name,
                    'employeeId' => $employee->id,
                    'active' => $isEmployeeActive,
                    'vacationBalance' => round($employeeVacationBalance, 2),
                ];
            }
        }

        return $data;
    }

    protected static $counter = 0;

    protected function pushData(array $data): self
    {
        $data['orderNo'] = static::$counter++;

        switch ($data['desc'] ?? ''){
            case 'used':
                $color = 'blue';
                break;
            case 'earned':
                $color = 'green';
                break;
            case 'start':
            case 'end':
                $color = 'red';
                break;
            default:
                $color = 'yellow';
        }
        $data['color'] = $color;
        $this->data['items'][] = (object)$data;
        $this->data['totalUsed'] += $data['usedDays'];
        $this->data['totalEarned'] += $data['earnedDays'];


        $this->data['color'] = $color;
        $this->data['id'] = $data['id'];

        return $this;
    }

    protected function bufferFactory(float $accumulatedDays = 0.00): array
    {
        return [
            'date' => null,
            'usedDays' => null,
            'earnedDays' => null,
            'desc' => null,
            'accumulatedDays' => $accumulatedDays,
            'id' => null
        ];
    }

}