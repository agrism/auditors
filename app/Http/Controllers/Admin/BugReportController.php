<?php

namespace App\Http\Controllers\Admin;

use App\BugReport;
use App\Enums\BugReportStatus;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BugReportController extends Controller
{
    /**
     * Display a listing of all users' bug reports and feedback.
     */
    public function index(Request $request)
    {
        $query = BugReport::with(['user', 'items', 'statuses'])->latest('created_at')->latest('id');

        // Status Filter
        $statusFilter = $request->get('status', 'all');
        if ($statusFilter !== 'all' && in_array($statusFilter, BugReportStatus::values())) {
            $query->where('status', $statusFilter);
        }

        // User Filter
        if ($request->filled('user_id')) {
            $userId = $request->get('user_id');
            if ($userId === 'guest') {
                $query->whereNull('user_id');
            } elseif ($userId === 'registered') {
                $query->whereNotNull('user_id');
            } else {
                $query->where('user_id', $userId);
            }
        }

        // Search Filter
        if ($request->filled('search')) {
            $s = '%' . trim($request->get('search')) . '%';
            $query->where(function ($q) use ($s) {
                $q->where('section', 'like', $s)
                  ->orWhere('id', 'like', $s)
                  ->orWhere('email', 'like', $s)
                  ->orWhere('url', 'like', $s)
                  ->orWhereHas('items', function ($iq) use ($s) {
                      $iq->where('message', 'like', $s);
                  })
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('name', 'like', $s)->orWhere('email', 'like', $s);
                  });
            });
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->get('from_date'));
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->get('to_date'));
        }

        $reports = $query->paginate(20)->withQueryString();

        $counts = [
            'all' => BugReport::count(),
            'new' => BugReport::where('status', BugReportStatus::NEW->value)->count(),
            'in_progress' => BugReport::where('status', BugReportStatus::IN_PROGRESS->value)->count(),
            'answered' => BugReport::where('status', BugReportStatus::ANSWERED->value)->count(),
            'closed' => BugReport::where('status', BugReportStatus::CLOSED->value)->count(),
        ];

        $users = User::orderBy('name')->get();

        return view('admin.bug-reports.index', compact(
            'reports',
            'counts',
            'statusFilter',
            'users'
        ));
    }

    /**
     * Display the specified bug report thread.
     */
    public function show($id)
    {
        $report = BugReport::with(['user', 'items.user', 'statuses.user'])->findOrFail($id);

        return view('admin.bug-reports.show', compact('report'));
    }

    /**
     * Post an official administrator reply to the ticket thread.
     */
    public function reply(Request $request, $id)
    {
        $report = BugReport::findOrFail($id);

        $request->validate([
            'message' => 'required|string|min:2|max:5000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:15360|mimes:jpeg,jpg,png,gif,webp,pdf,doc,docx,xls,xlsx,txt,csv,zip',
        ], [
            'message.required' => 'Lūdzu, ievadiet atbildes tekstu.',
            'message.min' => 'Atbildei jābūt vismaz 2 simbolus garai.',
            'attachments.*.max' => 'Fails nedrīkst pārsniegt 15 MB.',
        ]);

        $adminUser = Auth::user();

        $storedAttachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('bug_reports', 'public');
                    $storedAttachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'url' => Storage::disk('public')->url($path),
                        'size' => $file->getSize(),
                        'mime' => $file->getClientMimeType(),
                    ];
                }
            }
        }

        // Create thread item with is_admin_reply = true
        $item = $report->items()->create([
            'user_id' => $adminUser ? $adminUser->id : null,
            'message' => $request->input('message'),
            'attachments' => !empty($storedAttachments) ? $storedAttachments : null,
            'is_admin_reply' => true,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Update status to ANSWERED & record status history
        $report->changeStatus(
            BugReportStatus::ANSWERED,
            $adminUser ? $adminUser->id : null,
            'Atbilde sniegta no administrācijas paneļa'
        );

        // Notify client via async email
        $report->notifyClient($item);

        return redirect()->route('admin.bug-reports.show', $report->id)
            ->with('success', 'Atbilde veiksmīgi nosūtīta klientam.');
    }

    /**
     * Update bug report status directly from the admin panel.
     */
    public function updateStatus(Request $request, $id)
    {
        $report = BugReport::findOrFail($id);

        $request->validate([
            'status' => 'required|string|in:' . implode(',', BugReportStatus::values()),
            'comment' => 'nullable|string|max:500',
        ]);

        $adminUser = Auth::user();
        $newStatus = BugReportStatus::from($request->input('status'));
        $comment = $request->input('comment') ?: ('Statuss mainīts uz: ' . $newStatus->label());

        $report->changeStatus($newStatus, $adminUser ? $adminUser->id : null, $comment);

        return redirect()->back()->with('success', 'Pieteikuma statuss veiksmīgi atjaunināts: ' . $newStatus->label());
    }
}
