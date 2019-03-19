<?php
namespace Roxby\Logger;

/**
 * Logging client.
 * Currently uses logz.io as service
 */
class RoxbyLogger
{
    private $apiKey = null;
    private $application = null; // app identifier

    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

    /**
     * @param $apiKey string
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param $application string
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }

    /**
     * @param $message
     * @param array $data
     * @throws \Exception
     */
    public function critical($message, array $data = [])
    {
        $this->log('critical', $message, $data);
    }

    /**
     * @param $message
     * @param array $data
     * @throws \Exception
     */
    public function error($message, array $data = [])
    {
        $this->log('error', $message, $data);
    }

    /**
     * @param $message
     * @param array $data
     * @throws \Exception
     */
    public function info($message, array $data = [])
    {
        $this->log('info', $message, $data);
    }

    /**
     * @param $message
     * @param array $data
     * @throws \Exception
     */
    public function debug($message, array $data = [])
    {
        $this->log('debug', $message, $data);
    }

    /**
     * Add the log to list or send now
     *
     * @param string $type The log type
     * @param string $message The log message
     * @param array $data Additional data
     * @throws \Exception
     */
    private function log($type, $message, array $data)
    {
        $log = $this->makeLog($message, $data);
        $this->sendLog($log, $type);
    }

    /**
     * Send the log to Logz.io
     *
     * @param $log
     * @param string $type The log type
     * @return \Psr\Http\Message\StreamInterface
     * @throws \Exception
     */
    private function sendLog($log, $type = '')
    {
        $this->validateSendLog();
        $response = $this->getClient()->post('/', [
            'query' => [
                'token' => $this->apiKey,
                'type' => $type
            ],
            'body' => $log
        ]);
        return $response->getBody();
    }

    /**
     * Format the log to send
     *
     * @param string $message The log message
     * @param array $data Additional data
     * @return false|string
     */
    private function makeLog($message, array $data = [])
    {
        return json_encode(
            array_merge(
                [
                    'message' => $message,
                    'application' => $this->application
                ],
                $data
            )
        );
    }

    /**
     * Validate if log can be sent
     * @throws \Exception
     */
    private function validateSendLog()
    {
        if (!$this->apiKey || !$this->application) {
            throw new \Exception('You must specify Logz.io api key and your application');
        }
    }

    /**
     * @return \GuzzleHttp\Client
     */
    private function getClient()
    {
        return new \GuzzleHttp\Client([
            'base_uri' => 'https://listener.logz.io:8071'
        ]);
    }
    
    private function __construct()
    {
    }
}
