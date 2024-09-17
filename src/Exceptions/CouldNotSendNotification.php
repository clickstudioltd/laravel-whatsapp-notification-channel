<?php

declare(strict_types=1);

namespace NotificationChannels\WhatsApp\Exceptions;

use NotificationChannels\WhatsApp\Messages\WhatsAppTemplateMessage;

class CouldNotSendNotification extends \Exception
{
    public static function invalidMessageObject($message): self
    {
        $className = is_object($message) ? get_class($message) : 'Unknown';

        return new static("Notification was not sent. Message object class `{$className}` is invalid. It should be `".WhatsAppTemplateMessage::class.'`');
    }

    public static function missingFrom(): self
    {
        return new static('Notification was not sent. Missing `from` number.');
    }

    public static function invalidReceiver(): self
    {
        return new static(
            'The notifiable did not have a receiving phone number. Add a routeNotificationForWhatsApp method or a phone_number attribute to your notifiable.'
        );
    }
}
