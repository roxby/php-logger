<?php
namespace Roxby\Logger\Handlers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Monolog\Handler\Curl\Util;
use Roxby\Logger\RoxbyFormatter;
use Monolog\Formatter\FormatterInterface;
class LogitHandler extends AbstractProcessingHandler
{

    private $endpoint;
    private $apiKey;

    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        $this->apiKey = getenv("LOGIT_TOKEN");
        $this->endpoint = "https://api.logit.io/v2";
        parent::__construct($level, $bubble);
    }

    protected function write(array $record) :void
    {
        $this->send($record['formatted']);
    }


    protected function send($data)
    {
        $ch = curl_init();
        $options = [
            CURLOPT_POST => 1,
            CURLOPT_URL => $this->endpoint,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'LogType: default',
                'ApiKey:' . $this->apiKey
            ],
            CURLOPT_POSTFIELDS => $data,

        ];
        curl_setopt_array($ch, $options);

        Util::execute($ch);
    }

    protected function getDefaultFormatter() : FormatterInterface
    {
        return new RoxbyFormatter();
    }
}
