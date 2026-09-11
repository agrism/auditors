<?php

namespace App\Http\Controllers;

use App\BugReport;
use App\BugReportItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BugReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|min:3|max:5000',
            'email' => 'nullable|email|max:255',
            'section' => 'nullable|string|max:255',
            'url' => 'nullable|string|max:2048',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:15360|mimes:jpeg,jpg,png,gif,webp,pdf,doc,docx,xls,xlsx,txt,csv,zip',
        ], [
            'description.required' => 'Lūdzu, ievadiet kļūdas aprakstu vai ieteikumu.',
            'description.min' => 'Aprakstam jābūt vismaz 3 simbolus garam.',
            'email.email' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',
            'attachments.*.max' => 'Pievienotais fails nedrīkst pārsniegt 15 MB.',
            'attachments.*.mimes' => 'Neatbalstīts faila formāts. Atļautie: JPG, PNG, GIF, WEBP, PDF, DOC, XLS, TXT, ZIP.',
        ]);

        $user = Auth::user();

        // Process attachments
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

        $bugReport = DB::transaction(function () use ($request, $user, $storedAttachments) {
            // 1. Create main bug report / feedback ticket
            $report = BugReport::create([
                'user_id' => $user ? $user->id : null,
                'email' => $request->filled('email') ? trim($request->input('email')) : null,
                'section' => $request->filled('section') ? trim($request->input('section')) : null,
                'url' => $request->input('url', $request->headers->get('referer')),
                'status' => \App\Enums\BugReportStatus::NEW,
                'last_read_at' => now(),
            ]);

            // Record initial status in history
            $report->statuses()->create([
                'status' => \App\Enums\BugReportStatus::NEW->value,
                'user_id' => $user ? $user->id : null,
                'comment' => 'Pieteikums izveidots',
            ]);

            // 2. Create initial thread message with attachments
            $report->items()->create([
                'user_id' => $user ? $user->id : null,
                'message' => $request->input('description'),
                'attachments' => !empty($storedAttachments) ? $storedAttachments : null,
                'is_admin_reply' => false,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return $report;
        });

        // Notify administrator via async email
        $firstItem = $bugReport->items()->first();
        if ($firstItem) {
            $bugReport->notifyAdmin($firstItem, true);
        }

        $notifyEmail = $bugReport->email ? " | Contact email: {$bugReport->email}" : "";
        $sectionInfo = $bugReport->section ? " | Section: {$bugReport->section}" : "";
        $attachInfo = !empty($storedAttachments) ? " | Files: " . count($storedAttachments) : "";
        Log::info("Bug/Feedback report #{$bugReport->id} submitted by user: " . ($user ? "{$user->name} ({$user->email})" : "Guest") . "{$notifyEmail}{$sectionInfo}{$attachInfo} | URL: {$bugReport->url}");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paldies! Jūsu ziņojums ir veiksmīgi nosūtīts.',
                'report_id' => $bugReport->id,
            ]);
        }

        return redirect()->back()->with('success', 'Paldies! Jūsu ziņojums ir veiksmīgi nosūtīts.');
    }

    /**
     * Add reply / message to existing bug report thread
     */
    public function reply(Request $request, $id)
    {
        $bugReport = BugReport::findOrFail($id);

        $request->validate([
            'message' => 'required|string|min:2|max:5000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:15360|mimes:jpeg,jpg,png,gif,webp,pdf,doc,docx,xls,xlsx,txt,csv,zip',
        ]);

        $user = Auth::user();

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

        // In client side, the message is always a client reply
        $item = $bugReport->items()->create([
            'user_id' => $user ? $user->id : null,
            'message' => $request->input('message'),
            'attachments' => !empty($storedAttachments) ? $storedAttachments : null,
            'is_admin_reply' => false,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Update report status & history
        $bugReport->last_read_at = now();
        $bugReport->save();
        $bugReport->changeStatus(\App\Enums\BugReportStatus::IN_PROGRESS, $user ? $user->id : null, 'Atbilde no lietotāja');

        // Notify administrator via async email
        $bugReport->notifyAdmin($item, false);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Atbilde veiksmīgi pievienota.',
                'item_id' => $item->id,
            ]);
        }

        return redirect()->back()->with('success', 'Atbilde veiksmīgi pievienota.');
    }
}
