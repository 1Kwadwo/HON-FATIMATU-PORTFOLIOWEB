<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        
        // Add security headers to all responses
        $middleware->append(\App\Http\Middleware\SecurityHeadersMiddleware::class);
        
        // Force HTTPS in production
        $middleware->append(\App\Http\Middleware\ForceHttpsMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Log all exceptions to laravel.log
        $exceptions->report(function (Throwable $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        });

        // Send critical errors to admin email
        $exceptions->report(function (Throwable $e) {
            // Helper function to determine if error is critical
            $shouldReportCriticalError = function (Throwable $e): bool {
                $criticalExceptions = [
                    \Symfony\Component\HttpKernel\Exception\HttpException::class,
                    \Illuminate\Database\QueryException::class,
                    \ErrorException::class,
                ];

                foreach ($criticalExceptions as $exceptionClass) {
                    if ($e instanceof $exceptionClass) {
                        if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                            return $e->getStatusCode() >= 500;
                        }
                        return true;
                    }
                }
                return false;
            };
            
            // Only send email for critical errors in production
            if (app()->environment('production') && $shouldReportCriticalError($e)) {
                try {
                    \Illuminate\Support\Facades\Mail::raw(
                        "A critical error occurred:\n\n" .
                        "Message: {$e->getMessage()}\n" .
                        "File: {$e->getFile()}\n" .
                        "Line: {$e->getLine()}\n\n" .
                        "Trace:\n{$e->getTraceAsString()}",
                        function ($message) {
                            $message->to(config('mail.admin_email', config('mail.from.address')))
                                ->subject('Critical Error on ' . config('app.name'));
                        }
                    );
                } catch (\Exception $mailException) {
                    // Log mail failure but don't throw
                    \Illuminate\Support\Facades\Log::error('Failed to send critical error email', [
                        'error' => $mailException->getMessage(),
                    ]);
                }
            }
        });

        // Return JSON for API requests, render views for web requests
        $exceptions->render(function (Throwable $e, $request) {
            // Check if request expects JSON (API request)
            if ($request->expectsJson()) {
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                
                return response()->json([
                    'message' => app()->environment('production') 
                        ? 'An error occurred while processing your request.' 
                        : $e->getMessage(),
                    'error' => get_class($e),
                    'status' => $statusCode,
                ], $statusCode);
            }

            // For web requests, let Laravel handle the rendering (will use custom error views)
            return null;
        });
    })->create();
