<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $response = [
            'status' => 400,
            'message' => (string) $e->getMessage()
        ];

        if ($e instanceof HttpException) {
            $response['message'] = Response::$statusTexts[$e->getStatusCode()];
            $response['status'] = $e->getStatusCode();
        } elseif ($e instanceof NotFoundHttpException) {
            $response['message'] = Response::$statusTexts[Response::HTTP_NOT_FOUND];
            $response['status'] = Response::HTTP_NOT_FOUND;
        }

        if ($this->_isDebugMode()) {
            return parent::render($request, $e);
        } else {
            return response()->json(['error' => $response], $response['status']);
        }
    }

    protected function _isDebugMode()
    {
        return (boolean) env('APP_DEBUG');
    }
}
