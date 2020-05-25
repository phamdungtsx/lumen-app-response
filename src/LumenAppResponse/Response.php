<?php

namespace Phamdungtsx\LumenAppResponse;

use Phamdungtsx\LumenAppResponse\Exceptions\AppException;

trait Response
{
    protected $data = [];

    protected $resHeaders = [];

    protected $statusCode = 200;

    protected $errors = null;

    protected $validations = null;

    protected $message;

    protected $exception;

    public static $CODE = [
        200 => 'OK',
        403 => 'FORBIDDEN',
        404 => 'NOT_FOUND',
        422 => 'UNPROCESSABLE_ENTITY',
        500 => 'INTERNAL_SERVER_ERROR',
    ];

    public function addData($data)
    {
        $this->data = $data;
    }

    public function setResHeaders(array $resHeaders)
    {
        $this->resHeaders = $resHeaders;

        return $this;
    }

    public function setStatusCode(int $code)
    {
        $this->statusCode = $code;

        return $this;
    }

    public function addErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    public function addValidations(array $validations)
    {
        $this->validations = $validations;

        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message ? $message : self::$CODE[$this->statusCode];

        return $this;
    }

    public function setException(\Exception $e)
    {
        $this->exception = $e;

        return $this;
    }

    public function getResponse()
    {
        $data = [];

        $e = $this->exception;

        

        if ($e && $e instanceof \Exception) {
            $data['error'] = null;

            if($this->errors) {
                $data['error']['errors'] = $this->errors;
            }
            if($this->validations) {
                $data['error']['validations'] = $this->validations;
            }

            $errorCode     = $e->getCode() ?? $this->statusCode;
            $data['error']['code']    = $errorCode ?: $this->statusCode;
            $data['error']['message'] = $this->message;

            if (env('APP_DEBUG') === true) {
                $data['debug'] = [
                    'message' => $e->getMessage(),
                    'code'    => $e->getCode(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                ];
                if ($e instanceof AppException) {
                    $data['debug']['message'] = $e->getPrivateMessage();
                }

            }
        }

        $data['data'] = null;

        if ($this->data) {
            $data['data'] = $this->data;
        }

        return response()->json($data, $this->statusCode, $this->resHeaders);
    }
}
