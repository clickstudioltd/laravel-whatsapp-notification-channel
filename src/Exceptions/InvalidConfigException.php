<?php

declare(strict_types=1);

namespace NotificationChannels\WhatsApp\Exceptions;

class InvalidConfigException extends \Exception
{
    public static function missingConfig(): self
    {
        return new self('Missing config. You must set the API key.');
    }
}
