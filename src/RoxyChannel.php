<?php

namespace Roxby\Logger;

use Inpsyde\LogzIoMonolog\Handler\LogzIoHandler;

class RoxyChannel
{
    public static function getLogger($channelName)
    {
        $token = getenv("LOGZIO_TOKEN");
        $handler = new LogzIoHandler($token);
        $handler->setFormatter(new RoxbyFormatter());
        $log = new RoxbyLogger($channelName);
        $log->pushHandler($handler);
        return $log;
    }

}