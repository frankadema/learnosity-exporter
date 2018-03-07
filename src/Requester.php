<?php

namespace App;

use LearnositySdk\Request\DataApi;

/**
 * Class Requester
 */
class Requester
{
    /**
     * @var array
     */
    private $config;

    /**
     * Requester constructor.
     * @param array $config
     */
    public function __construct(
        array $config
    ) {
        $this->config = $config;
    }

    /**
     * @param string      $endpoint
     * @param string|null $next
     *
     * @return array
     */
    public function get(string $endpoint, string $next = null)
    {
        // create datetime
        $dateTime = new \DateTime();
        $dateTime->setTimezone(new \DateTimeZone('GMT'));

        // build request
        $action = 'get';
        $securityPacket = [
            'consumer_key' => $this->config['learnosity_key'],
            'domain' => $this->config['learnosity_domain'],
            'timestamp' => $dateTime->format('Ymd-Hi'),
        ];

        // build request packet
        $requestPacket = [];

        if (!is_null($next)) {
            $requestPacket['next'] = $next;
        }

        // send request
        $dataApi = new DataApi();

        $remote = $dataApi->request(
            $endpoint,
            $securityPacket,
            $this->config['learnosity_secret'],
            $requestPacket,
            $action
        );

        return $remote->json();
    }
}
