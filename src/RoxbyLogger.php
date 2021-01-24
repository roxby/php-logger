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
     * @param  int $level The logging level
     * @param  string $message The log message
     * @param  array $context The log context
     * @param int|\Monolog\int $level
     * @param \Monolog\string|string $message
     * @param array $context
     * @return bool
     */
    public function addRecord($level, $message, array $context = array()) :bool
    {
        $context['application'] = $this->application;
        $context['host'] = $this->host;
        return parent::addRecord($level, $message, $context);
    }
}
