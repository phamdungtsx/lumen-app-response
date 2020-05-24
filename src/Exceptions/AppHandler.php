<?php

namespace Phamdungtsx\Exceptions;

use Phamdungtsx\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class AppHandler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            $this->setStatusCode(404)
                 ->setMessage($exception->getMessage())
                 ->setException($exception);
        } else if ($exception instanceof AppException) {
            $this->setStatusCode($exception->getStatusCode())
                 ->setMessage($exception->getMessage())
                 ->setException($exception);
        } else if ($exception instanceof \Exception) {
            $this->setStatusCode(500)
                 ->setException($exception);
        }

        return $this->getResponse();
    }

}