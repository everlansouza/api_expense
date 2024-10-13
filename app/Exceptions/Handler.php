<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
    }

    /***
     * Render an exception into an HTTO response.
     * 
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exeception
     * @return \Illuminate\Http\Response
     */

    public function render($request, Throwable $exeception)
    {
        if ($request->is('api/*')) {
            if ($exeception instanceof ValidationException) {
                return response()->json(
                    $exeception->errors(),
                    $exeception->status
                );
            }
        }

        return parent::render($request, $exeception);
    }
}
