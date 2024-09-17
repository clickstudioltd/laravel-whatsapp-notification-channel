<?php

namespace NotificationChannels\WhatsApp;

use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use NotificationChannels\WhatsApp\Exceptions\CouldNotSendNotification;
use NotificationChannels\WhatsApp\Messages\WhatsAppMessage;

class WhatsAppChannel
{
    /**
     * @var WhatsApp
     */
    protected $whatsapp;

    /**
     * @var Dispatcher
     */
    protected $events;

    /**
     * WhatsAppChannel constructor.
     *
     * @param WhatsApp $whatsapp
     * @param Dispatcher $events
     */
    public function __construct(WhatsApp $whatsapp, Dispatcher $events)
    {
        $this->whatsapp = $whatsapp;
        $this->events = $events;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return mixed
     * @throws Exception
     */
    public function send($notifiable, Notification $notification)
    {
        try {
            $message = $notification->toWhatsApp($notifiable);

            if (! $message instanceof WhatsAppMessage) {
                throw CouldNotSendNotification::invalidMessageObject($message);
            }

            $to = $this->getTo($notifiable, $notification, $message);

            return $this->whatsapp->sendMessage($message, $to);
        } catch (Exception $exception) {
            $event = new NotificationFailed(
                $notifiable,
                $notification,
                'whatsapp',
                [
                    'message' => $exception->getMessage(),
                    'exception' => $exception
                ]
            );

            $this->events->dispatch($event);

            if ($this->whatsapp->config->isIgnoredErrorCode($exception->getCode())) {
                return;
            }

            throw $exception;
        }
    }

    /**
     * Get the address to send a notification to.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @param WhatsAppMessage $message
     *
     * @return mixed
     * @throws CouldNotSendNotification
     */
    protected function getTo($notifiable, $notification, $message)
    {
        if ($message->getTo()) {
            return $message->getTo();
        }
        if ($notifiable->routeNotificationFor(self::class, $notification)) {
            return $notifiable->routeNotificationFor(self::class, $notification);
        }
        if ($notifiable->routeNotificationFor('WhatsApp', $notification)) {
            return $notifiable->routeNotificationFor('WhatsApp', $notification);
        }
        if (isset($notifiable->phone_number)) {
            return $notifiable->phone_number;
        }

        throw CouldNotSendNotification::invalidReceiver();
    }
}
