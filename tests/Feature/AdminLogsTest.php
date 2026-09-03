<?php

namespace Tests\Feature;

use App\Log;
use App\User;
use Tests\TestCase;

class AdminLogsTest extends TestCase
{
    public function test_activity_is_logged_by_middleware(): void
    {
        $initialCount = Log::count();

        $this->get('/login');

        $this->assertGreaterThan($initialCount, Log::count());
    }

    public function test_admin_can_view_activity_logs(): void
    {
        $admin = User::where('is_admin', 1)->first() ?? User::factory()->create(['is_admin' => 1]);

        $response = $this->actingAs($admin)->get(route('admin.logs.index'));

        $response->assertStatus(200);
        $response->assertSee('Lietotāju aktivitātes žurnāls');
        $response->assertSee('Kopā ierakstu');
    }

    public function test_non_admin_cannot_view_activity_logs(): void
    {
        $user = User::where('is_admin', 0)->orWhereNull('is_admin')->first();
        if ($user) {
            $response = $this->actingAs($user)->get(route('admin.logs.index'));
            $response->assertRedirect('/client');
        }
    }

    public function test_user_with_email_7924_is_not_logged(): void
    {
        $user = User::where('email', '7924@inbox.lv')->first();
        if ($user) {
            $countBefore = Log::count();
            $this->actingAs($user)->get('/client');
            $this->assertEquals($countBefore, Log::count());
        }
    }
}
