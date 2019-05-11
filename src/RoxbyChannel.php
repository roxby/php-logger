<?php

namespace Roxby\Logger;

use Roxby\Logger\Handlers\LogitHandler;

class RoxbyChannel
{
    public static function getLogger($channelName)
    {
        $handler = new LogitHandler();
        $handler->setFormatter(new RoxbyFormatter());
        $log = new RoxbyLogger($channelName);
        $log->pushHandler($handler);
        return $log;
    }

}