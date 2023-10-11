<?php

namespace App\Exception;

use RuntimeException;

class DatabaseConnectionFull extends RuntimeException
{
    private string $messageException;
    private int $httpStatusCode;

    public function __construct(string $messageException,int $httpStatusCode)
    {
        parent::__construct($messageException);
        $this->messageException =   $messageException;
        $this->httpStatusCode   =   $httpStatusCode;
    }

    public function getMessageException(): string
    {
        return $this->messageException;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

}