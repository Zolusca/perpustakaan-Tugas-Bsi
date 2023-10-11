<?php
namespace App\Libraries;
use App\Entity\User;
use Monolog\Logger;

class LoggerCreations
{
    /**
     * @return Logger logger object
     * @param string $class name of class where used
     * this method for creating logger monolog object where the output on console
     * @example logger    = LoggerCreations::LoggerCreations(User::class);
     */
    public static function LoggerCreations(string $class): Logger
    {
        $logger = new Logger(name: $class);
        $logger->pushHandler(new \Monolog\Handler\StreamHandler("php://stderr"));

        return $logger;
    }
}