<?php
namespace My;

class Logger
{
    public $logger;

    function __construct() {
        $this->logger = new \Monolog\Logger('myLogger');
        $this->logger->pushHandler(
            new \Monolog\Handler\StreamHandler(__DIR__ . '/../logs/app.log')
        );
    }
}
