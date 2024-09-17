<?php

namespace NotificationChannels\WhatsApp;

use NotificationChannels\WhatsApp\Exceptions\CouldNotSendNotification;
use NotificationChannels\WhatsApp\Exceptions\HttpException;
use NotificationChannels\WhatsApp\Messages\WhatsAppMessage;
use NotificationChannels\WhatsApp\Messages\WhatsAppTemplateMessage;

class WhatsApp
{
    /** @var WhatsAppClient */
    protected $client;

    /** @var WhatsAppConfig */
    public $config;

    public function __construct(WhatsAppClient $client, WhatsAppConfig $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    /**
     * Send a WhatsAppMessage to a phone number.
     *
     * @param WhatsAppMessage $message
     * @param string|null $to
     *
     * @return mixed
     * @throws HttpException
     * @throws CouldNotSendNotification
     */
    public function sendMessage(WhatsAppMessage $message, ?string $to)
    {
        if ($message instanceof WhatsAppTemplateMessage) {
            return $this->sendTemplateMessage($message, $to);
        }

        throw CouldNotSendNotification::invalidMessageObject($message);
    }

    /**
     * Send a template message using on WhatsApp.
     *
     * @param WhatsAppTemplateMessage $message
     * @param string|null $to
     *
     * @return array
     * @throws CouldNotSendNotification
     * @throws HttpException
     */
    protected function sendTemplateMessage(WhatsAppTemplateMessage $message, ?string $to): array
    {
        $debugTo = $this->config->getDebugTo();

        if (! empty($debugTo)) {
            $to = $debugTo;
        }

        $from = $this->getFrom($message);

        if (empty($from)) {
            throw CouldNotSendNotification::missingFrom();
        }

        return $this->client->enqueueMessage([
            'from' => $from,
            'to' => $to,
            'type' => 'template',
            'template' => $message->getTemplate(),
        ]);
    }

    /**
     * Get the from address from message, or config.
     *
     * @param WhatsAppMessage $message
     * @return string|null
     */
    protected function getFrom(WhatsAppMessage $message): ?string
    {
        return $message->getFrom() ?: $this->config->getFrom();
    }
}
