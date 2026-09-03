<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Redirect to login on CSRF/session expiration (HTTP 419 / TokenMismatchException)
        $this->renderable(function (TokenMismatchException $e, $request) {
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json([
                    'message' => 'Your session has expired. Please log in again.',
                    'redirect' => route('login'),
                ], 419);
            }

            return redirect()->guest(route('login'))
                ->with('error', 'Jūsu sesija ir beigusies. Lūdzu, autorizējieties no jauna.');
        });

        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                if ($request->expectsJson() || $request->header('X-Livewire')) {
                    return response()->json([
                        'message' => 'Your session has expired. Please log in again.',
                        'redirect' => route('login'),
                    ], 419);
                }

                return redirect()->guest(route('login'))
                    ->with('error', 'Jūsu sesija ir beigusies. Lūdzu, autorizējieties no jauna.');
            }
        });
    }
}
