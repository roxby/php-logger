<?php
namespace Roxby\Logger;
use DateTimeImmutable;
use Monolog\Logger;

class RoxbyLogger extends Logger {

    private $application;
    private $host;

    public function __construct($name, $handlers = array(), $processors = array())
    {
        parent::__construct($name, $handlers, $processors);
        $this->application = getenv('APP_NAME');
        $this->host = gethostname();
    }


    /**
     * Adds a log record.
     * @param int $level
     * @param string $message
     * @param array $context
     * @param DateTimeImmutable|NULL $datetime
     * @return bool
     */
    public function addRecord(int $level, string  $message, array $context = array(), DateTimeImmutable $datetime = NULL) :bool
    {
        $context['application'] = $this->application;
        $context['host'] = $this->host;
        return parent::addRecord($level, $message, $context);
    }
}
