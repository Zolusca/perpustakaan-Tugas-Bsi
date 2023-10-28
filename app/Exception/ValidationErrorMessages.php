<?php

namespace App\Exception;

class ValidationErrorMessages extends \RuntimeException
{
    private $dataInformation;
    public function __construct(string $messageException, $dataInformation=null)
    {
        parent::__construct($messageException);

        $this->dataInformation  =   $dataInformation;
    }
    public function getDataInformation()
    {
        return $this->dataInformation;
    }
}