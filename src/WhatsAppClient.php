<?php

namespace NotificationChannels\WhatsApp;

use GuzzleHttp\Exception\GuzzleException;
use NotificationChannels\WhatsApp\Exceptions\HttpException;

class WhatsAppClient
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    public function __construct($options = [])
    {
        $this->client = new \GuzzleHttp\Client(array_merge($options, [
            'base_uri' => 'https://api.ycloud.com/v2/'
        ]));
    }

    /**
     * Enqueues an outbound WhatsApp message for sending.
     *
     * @param array $payload Message payload.
     * @param array $options Request options.
     *
     * @return array Result of the request.
     * @throws HttpException
     */
    public function enqueueMessage(array $payload, $options = [])
    {
        try {
            $response = $this->client->post('whatsapp/messages', array_merge(array_merge_recursive($options, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]), [
                'json' => $payload
            ]));

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
