<?php

namespace Tests\Feature;

use App\BugReport;
use App\BugReportItem;
use App\Enums\BugReportStatus;
use App\User;
use Tests\TestCase;

class AdminBugReportsTest extends TestCase
{
    public function test_non_admin_cannot_access_admin_bug_reports(): void
    {
        $user = User::where('is_admin', 0)->orWhereNull('is_admin')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Regular User',
                'email' => 'regular_user_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 0,
            ]);
        }

        $response = $this->actingAs($user)->get(route('admin.bug-reports.index'));
        $response->assertRedirect('/client');
    }

    public function test_admin_can_view_all_users_bug_reports(): void
    {
        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin_user_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 1,
            ]);
        }

        $regularUser = User::where('is_admin', 0)->orWhereNull('is_admin')->first();
        if (!$regularUser) {
            $regularUser = User::create([
                'name' => 'Client Test',
                'email' => 'client_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 0,
            ]);
        }

        // Create a ticket for regular user
        $report = BugReport::create([
            'user_id' => $regularUser->id,
            'email' => $regularUser->email,
            'section' => 'Rēķini › Jauna rēķina forma',
            'url' => 'https://auditors.lv/client',
            'status' => BugReportStatus::NEW,
        ]);

        $report->items()->create([
            'user_id' => $regularUser->id,
            'message' => 'Lūdzu pārbaudīt PVN kalkulāciju',
            'is_admin_reply' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.bug-reports.index'));
        $response->assertStatus(200);
        $response->assertSee('Klientu saziņas un kļūdu ziņojumi');
        $response->assertSee('#' . $report->id);
        $response->assertSee('Lūdzu pārbaudīt PVN kalkulāciju');
    }

    public function test_admin_can_view_single_report_details(): void
    {
        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin_user_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 1,
            ]);
        }

        $report = BugReport::create([
            'user_id' => $admin->id,
            'section' => 'Darba laiks',
            'status' => BugReportStatus::NEW,
        ]);
        $report->items()->create([
            'user_id' => $admin->id,
            'message' => 'Detalizēts apraksts testa pieteikumam',
            'is_admin_reply' => false,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.bug-reports.show', $report->id));
        $response->assertStatus(200);
        $response->assertSee('PIETEIKUMS #' . $report->id);
        $response->assertSee('Detalizēts apraksts testa pieteikumam');
    }

    public function test_admin_reply_sets_is_admin_reply_and_changes_status_to_answered(): void
    {
        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin_user_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 1,
            ]);
        }

        $clientUser = User::where('is_admin', 0)->orWhereNull('is_admin')->first();
        if (!$clientUser) {
            $clientUser = User::create([
                'name' => 'Client Demo',
                'email' => 'client_demo_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 0,
            ]);
        }

        $report = BugReport::create([
            'user_id' => $clientUser->id,
            'status' => BugReportStatus::NEW,
        ]);
        $report->items()->create([
            'user_id' => $clientUser->id,
            'message' => 'Vai var pievienot jaunu funkciju?',
            'is_admin_reply' => false,
        ]);

        // Post admin reply
        $response = $this->actingAs($admin)->post(route('admin.bug-reports.reply', $report->id), [
            'message' => 'Labdien! Jūsu ieteikums ir pieņemts izstrādei.',
        ]);

        $response->assertRedirect(route('admin.bug-reports.show', $report->id));

        $report->refresh();
        $this->assertEquals(BugReportStatus::ANSWERED, $report->status_enum);

        $latestItem = $report->items()->latest('id')->first();
        $this->assertTrue((bool)$latestItem->is_admin_reply);
        $this->assertEquals('Labdien! Jūsu ieteikums ir pieņemts izstrādei.', $latestItem->message);
        $this->assertEquals($admin->id, $latestItem->user_id);

        // Verify status history
        $latestStatus = $report->statuses()->latest('id')->first();
        $this->assertEquals(BugReportStatus::ANSWERED->value, $latestStatus->status_value);
        $this->assertEquals($admin->id, $latestStatus->user_id);
    }

    public function test_admin_can_manually_update_status(): void
    {
        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin_user_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 1,
            ]);
        }

        $report = BugReport::create([
            'status' => BugReportStatus::NEW,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.bug-reports.updateStatus', $report->id), [
            'status' => BugReportStatus::CLOSED->value,
            'comment' => 'Kļūda atrisināta ar laidienu v2.4',
        ]);

        $response->assertSessionHas('success');

        $report->refresh();
        $this->assertEquals(BugReportStatus::CLOSED, $report->status_enum);

        $latestStatus = $report->statuses()->latest('id')->first();
        $this->assertEquals(BugReportStatus::CLOSED->value, $latestStatus->status_value);
        $this->assertEquals('Kļūda atrisināta ar laidienu v2.4', $latestStatus->comment);
    }

    public function test_client_submission_sends_async_email_notification_to_admin(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $clientUser = User::where('is_admin', 0)->orWhereNull('is_admin')->first();
        if (!$clientUser) {
            $clientUser = User::create([
                'name' => 'Client Sender',
                'email' => 'client_sender_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 0,
            ]);
        }

        $response = $this->actingAs($clientUser)->post(route('bug-reports.store'), [
            'description' => 'Atrasta problēma ar PVN deklarācijas eksportu',
            'section' => 'Atskaites > PVN',
            'url' => 'https://auditors.lv/client/reports',
        ]);

        $response->assertSessionHas('success');

        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\NewBugReportMessageMail::class, function ($mail) {
            return $mail->hasTo('7924@inbox.lv') 
                && $mail->hasFrom('noreplay@auditors.lv')
                && $mail->isNewThread === true;
        });
    }

    public function test_client_reply_sends_async_email_notification_to_admin(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $clientUser = User::where('is_admin', 0)->orWhereNull('is_admin')->first();
        if (!$clientUser) {
            $clientUser = User::create([
                'name' => 'Client Sender',
                'email' => 'client_sender_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 0,
            ]);
        }

        $report = BugReport::create([
            'user_id' => $clientUser->id,
            'status' => BugReportStatus::NEW,
        ]);
        $report->items()->create([
            'user_id' => $clientUser->id,
            'message' => 'Sākotnējā ziņa',
            'is_admin_reply' => false,
        ]);

        $response = $this->actingAs($clientUser)->post(route('bug-reports.reply', $report->id), [
            'message' => 'Papildu precizējums no klienta puses',
        ]);

        $response->assertSessionHas('success');

        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\NewBugReportMessageMail::class, function ($mail) {
            return $mail->hasTo('7924@inbox.lv') 
                && $mail->hasFrom('noreplay@auditors.lv')
                && $mail->isNewThread === false;
        });
    }

    public function test_admin_reply_does_not_send_notification_to_admin(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin_user_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 1,
            ]);
        }

        $report = BugReport::create([
            'user_id' => $admin->id,
            'status' => BugReportStatus::NEW,
        ]);
        $report->items()->create([
            'user_id' => $admin->id,
            'message' => 'Klienta ziņa',
            'is_admin_reply' => false,
        ]);

        $this->actingAs($admin)->post(route('admin.bug-reports.reply', $report->id), [
            'message' => 'Atbilde no administratora',
        ]);

        \Illuminate\Support\Facades\Mail::assertNotQueued(\App\Mail\NewBugReportMessageMail::class);
    }

    public function test_admin_reply_sends_async_email_notification_to_client(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin_user_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 1,
            ]);
        }

        $clientUser = User::where('is_admin', 0)->orWhereNull('is_admin')->first();
        if (!$clientUser) {
            $clientUser = User::create([
                'name' => 'Client Recipient',
                'email' => 'client_recipient_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 0,
            ]);
        }

        $report = BugReport::create([
            'user_id' => $clientUser->id,
            'status' => BugReportStatus::NEW,
        ]);
        $report->items()->create([
            'user_id' => $clientUser->id,
            'message' => 'Klienta jautājums par atskaitēm',
            'is_admin_reply' => false,
        ]);

        $this->actingAs($admin)->post(route('admin.bug-reports.reply', $report->id), [
            'message' => 'Labdien! Esam atrisinājuši problēmu.',
        ]);

        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\AdminBugReportReplyMail::class, function ($mail) use ($clientUser) {
            return $mail->hasTo($clientUser->email)
                && $mail->hasFrom('noreplay@auditors.lv');
        });
    }

    public function test_admin_reply_prioritizes_custom_contact_email_over_user_email(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $admin = User::where('is_admin', 1)->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin_user_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 1,
            ]);
        }

        $clientUser = User::where('is_admin', 0)->orWhereNull('is_admin')->first();
        if (!$clientUser) {
            $clientUser = User::create([
                'name' => 'Client Recipient',
                'email' => 'client_account_' . uniqid() . '@test.lv',
                'password' => bcrypt('secret'),
                'is_admin' => 0,
            ]);
        }

        $customEmail = 'custom_preferred_' . uniqid() . '@inbox.lv';

        $report = BugReport::create([
            'user_id' => $clientUser->id,
            'email' => $customEmail,
            'status' => BugReportStatus::NEW,
        ]);
        $report->items()->create([
            'user_id' => $clientUser->id,
            'message' => 'Klienta jautājums ar lūgumu atbildēt uz citu e-pastu',
            'is_admin_reply' => false,
        ]);

        $this->actingAs($admin)->post(route('admin.bug-reports.reply', $report->id), [
            'message' => 'Labdien! Atbilde nosūtīta uz Jūsu norādīto e-pastu.',
        ]);

        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\AdminBugReportReplyMail::class, function ($mail) use ($customEmail) {
            return $mail->hasTo($customEmail)
                && $mail->hasFrom('noreplay@auditors.lv');
        });
    }
}

