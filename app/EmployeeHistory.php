<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Employee
 *
 * @property int $id
 * @property string $name
 * @property string $role
 * @property int $company_id
 * @property string $registration_number
 * @property string $description
 */
class EmployeeHistory extends Model
{
    use SoftDeletes, HasFactory;

    const TYPE_USED_VACATION = 'used_vacation';
    const TYPE_EARNED_VACATION = 'earned_vacation';
    const TYPE_START = 'start';
    const TYPE_END = 'end';

    protected $table = 'employee_histories';


    public function hasStarted(
        int $companyId,
        int $employeeId
    ): bool
    {
        return boolval($this->where('employee_id',
            $employeeId)
            ->where('type',
                self::TYPE_START)
            ->where('company_id',
                $companyId)
            ->first());
    }

    public function hasEnded(
        int $companyId,
        int $employeeId
    ): bool
    {
        return boolval($this->where('employee_id',
            $employeeId)
            ->where('company_id',
                $companyId)
            ->where('type',
                self::TYPE_END)
            ->first());
    }

    public function active(
        int    $companyId,
        int    $employeeId,
        string $date
    ): bool
    {
        return boolval($this->where('employee_id',
            $employeeId)
            ->where('company_id',
                $companyId)
            ->where('date',
                $date)
            ->where('date',
                '>',
                $this->getStartDate($companyId,
                    $employeeId))
            ->where('date',
                '<',
                $this->getEndDate($companyId,
                    $employeeId))
            ->first());
    }


    public function getStartDate(
        int $companyId,
        int $employeeId
    ): ?string
    {
        return $this->where('company_id',
                $companyId)
                ->where('employee_id',
                    $employeeId)
                ->where('type',
                    self::TYPE_START)
                ->first()->date ?? null;
    }


    private function getEndDate(
        int $companyId,
        int $employeeId
    ): ?string
    {
        return $this->where('company_id',
                $companyId)
                ->where('employee_id',
                    $employeeId)
                ->where('type',
                    self::TYPE_END)
                ->first()->date ?? null;
    }

}