<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\BugReport;

class BugReportItem extends Model
{
    protected $table = 'bug_report_items';

    protected $fillable = [
        'bug_report_id',
        'user_id',
        'message',
        'attachments',
        'is_admin_reply',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_admin_reply' => 'boolean',
    ];

    public function report()
    {
        return $this->belongsTo(BugReport::class, 'bug_report_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
