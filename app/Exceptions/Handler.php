<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;
use Illuminate\Http\RedirectResponse;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Friendly handling for CSRF token mismatch (419 Page Expired)
        if ($exception instanceof TokenMismatchException) {
            // If the request expects JSON, return a 419 JSON response
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Page expired. Please refresh the page and try again.'], 419);
            }

            // Redirect back with old input and a helpful flash message
            return redirect()->back()->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', 'Your session expired. Please try again.');
        }

        return parent::render($request, $exception);
    }
}
