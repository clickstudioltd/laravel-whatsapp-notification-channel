<?php

declare(strict_types=1);

namespace NotificationChannels\WhatsApp\Exceptions;

use GuzzleHttp\Exception\TransferException;

class HttpException extends TransferException
{
    public function __construct($message = '', $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
