<?php

namespace Phamdungtsx;

use App\Exceptions\AppException;

trait Response
{
    protected $data = [];

    protected $headers = [];

    protected $statusCode = 200;

    protected $errors;

    protected $message;

    protected $exception;

    public static $CODE = [
        404 => 'NOT_FOUND',
        422 => 'UNPROCESSABLE_ENTITY',
        500 => 'INTERNAL_SERVER_ERROR',
    ];

    public function addData($data)
    {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public function setHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->headers[$key] = $value;
        }

        return $this;
    }

    public function setStatusCode(int $code)
    {
        $this->statusCode = $code;

        return $this;
    }

    public function addErrors(array $errors)
    {
        foreach ($errors as $key => $value) {
            $this->errors[$key] = $value;
        }

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

        if ($this->data) {
            $data['data'] = $this->data;
        }
        $e = $this->exception;

        if ($e && $e instanceof \Exception) {

            if ($this->errors || $e) {
                $errorCode     = $e->getCode() ?? $this->statusCode;
                $data['error'] = [
                    'code'    => $errorCode ?: $this->statusCode,
                    'message' => $this->message,
                ];
                if ($this->errors) {
                    $data['error']['errors'] = $this->errors;
                }
            }

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

        return response()->json($data, $this->statusCode, $this->headers);
    }
}
