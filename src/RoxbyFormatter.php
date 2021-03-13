<?php
namespace Roxby\Logger;
use Monolog\Formatter\JsonFormatter;

class RoxbyFormatter extends JsonFormatter {

    /**
     * yyyy-MM-dd'T'HH:mm:ss.SSSZ
     */
    const DATETIME_FORMAT = 'c';

    /**
     * Overrides the default batch mode to new lines for compatibility with the Logz.io bulk API.
     * @param int|void  $batchMode
     * @param bool|void $appendNewline
     */
    public function __construct($batchMode = self::BATCH_MODE_NEWLINES, $appendNewline = true)
    {
        parent::__construct($batchMode, $appendNewline);
    }

    /**
     * Appends the '@timestamp' parameter for Logz.io.
     * Flattens context values, removes extra if empty
     * @param array $record
     * @return string
     */
    public function format(array $record) : string
    {
        if (isset($record['datetime']) && ( $record['datetime'] instanceof \DateTimeInterface )) {
            $record['@timestamp'] = $record['datetime']->format(self::DATETIME_FORMAT);
            unset($record['datetime']);
        }

        if (isset($record['context']) && is_array($record['context'])) {
            $context = array_filter($record['context'], 'self::filter');
            foreach ($context as $key => $value) {
                $record[$key] = $value;
            }
            unset($record['context']);
        }

        if (isset($record['extra']) && is_array($record['extra'])) {
            $record['extra'] = array_filter($record['extra'], 'self::filter');
            if(empty($record['extra'])) {
                unset($record['extra']);
            }
        }
        $record['application'] = getenv('APP_NAME');
        $record['host'] = gethostname();
        return parent::format($record);
    }

    private static function filter($val)
    {
        return ($val !== NULL && $val !== '');
    }
}
