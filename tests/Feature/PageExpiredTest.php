<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PageExpiredTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::post('/test-csrf-expired', function () {
            throw new \Illuminate\Session\TokenMismatchException('CSRF token mismatch');
        });

        Route::get('/test-419-abort', function () {
            abort(419, 'Page expired');
        });
    }

    public function test_token_mismatch_redirects_to_login(): void
    {
        $response = $this->post('/test-csrf-expired');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_token_mismatch_json_request_returns_419_with_redirect(): void
    {
        $response = $this->postJson('/test-csrf-expired');

        $response->assertStatus(419);
        $response->assertJsonStructure(['message', 'redirect']);
    }

    public function test_http_419_abort_redirects_to_login(): void
    {
        $response = $this->get('/test-419-abort');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }
}
