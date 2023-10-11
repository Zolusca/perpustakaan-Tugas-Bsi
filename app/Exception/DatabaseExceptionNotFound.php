<?php

namespace App\Exception;
use CodeIgniter\HTTP\Response;
use RuntimeException;

class DatabaseExceptionNotFound extends RuntimeException
{
    private string $messageException;
    private int $httpStatusCode;
    private $dataInformation;

    public function __construct(string $messageException,int $httpStatusCode, $dataInformation=null)
    {
        parent::__construct($messageException);
        $this->messageException =   $messageException;
        $this->httpStatusCode   =   $httpStatusCode;
        $this->dataInformation  =   $dataInformation;
    }

    public function getMessageException(): string
    {
        return $this->messageException;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getDataInformation(): object
    {
        return $this->dataInformation;
    }

}