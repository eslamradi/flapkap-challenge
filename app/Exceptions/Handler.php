<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // format validation error response
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error',
                    'data' => [
                        'errors' => $e->errors(),
                    ]
                ], 500);
            }
        });

        // format general errors response
        $this->renderable(function (Throwable $e, $request) {
            if ($request->wantsJson()) {
                $data = [
                    'message' => $e->getMessage()
                ];
                if (!app()->environment('production')) {
                    $data['file'] = $e->getFile();
                    $data['line'] = $e->getLine();
                    $data['trace'] = $e->getTrace();
                }
                return response()->json([
                    'status' => false,
                    'message' => 'Error',
                    'data' => $data ?? [],
                ], 500);
            }
        });
    }
}
