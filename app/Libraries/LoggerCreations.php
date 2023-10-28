<?php
namespace App\Libraries;
use Monolog\Logger;
use function PHPUnit\Framework\isEmpty;

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
        $logger->pushHandler(new \Monolog\Handler\StreamHandler(WRITEPATH."logsApp/logapp.log"));

        return $logger;
    }

}