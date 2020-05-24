<?php

namespace Phamdungtsx\Exceptions;

use Exception;

class AppException extends Exception
{
    protected $code = 0;

    protected $message = null;

    protected $statusCode = 500;

    protected $privateMessage;

    public function __construct()
    {
        parent::__construct($this->message, $this->code);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $code)
    {
        $this->statusCode = $code;
    }

    public function getPrivateMessage()
    {
        return $this->privateMessage;
    }

    public function setPrivateMessage(string $message)
    {
        $this->privateMessage = $message;
    }
}
