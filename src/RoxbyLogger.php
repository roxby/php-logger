<?php
namespace Roxby\Logger;
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
     *
     * @param  int $level The logging level
     * @param  string $message The log message
     * @param  array $context The log context
     * @return void Whether the record has been processed
     */
    public function addRecord($level, $message, array $context = array())
    {
        $context['application'] = $this->application;
        $context['host'] = $this->host;
        parent::addRecord($level, $message, $context);
    }
}