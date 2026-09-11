<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Enums\BugReportStatus as BugReportStatusEnum;
use App\User;
use App\BugReport;

class BugReportStatus extends Model
{
    protected $table = 'bug_report_statuses';

    protected $fillable = [
        'bug_report_id',
        'status',
        'user_id',
        'comment',
    ];

    protected $casts = [
        'status' => BugReportStatusEnum::class,
    ];

    public function bugReport()
    {
        return $this->belongsTo(BugReport::class, 'bug_report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get string value of status
     */
    public function getStatusValueAttribute(): string
    {
        if ($this->status instanceof BugReportStatusEnum) {
            return $this->status->value;
        }

        return (string) ($this->status ?? 'new');
    }

    /**
     * Get enum representation of status
     */
    public function getStatusEnumAttribute(): BugReportStatusEnum
    {
        if ($this->status instanceof BugReportStatusEnum) {
            return $this->status;
        }

        return BugReportStatusEnum::tryFrom((string) $this->status) ?? BugReportStatusEnum::NEW;
    }
}
