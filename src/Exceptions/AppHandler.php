<?php

namespace Phamdungtsx\Exceptions;

use Phamdungtsx\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class AppHandler extends ExceptionHandler
{
    use Response;

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
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof UnauthorizedException) {
            $this->setStatusCode(403)
                 ->setMessage(trans('auth.unauthorized'))
                 ->setException($exception);
        }
        else if ($exception instanceof NotFoundHttpException) {
            $this->setStatusCode(404)
                 ->setMessage($exception->getMessage())
                 ->setException($exception);
        } else if ($exception instanceof AppException) {
            $this->setStatusCode($exception->getStatusCode())
                 ->setMessage($exception->getMessage())
                 ->addErrors($exception->getErrors())
                 ->addValidations($exception->getValidations())
                 ->setException($exception);
        } else if ($exception instanceof \Exception) {
            $this->setStatusCode(500)
                 ->setException($exception);
        }

        return $this->getResponse();
    }
}
