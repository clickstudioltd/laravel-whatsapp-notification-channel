<?php

declare(strict_types=1);

namespace NotificationChannels\WhatsApp\Tests\Integration;

use NotificationChannels\WhatsApp\WhatsAppProvider;
use Orchestra\Testbench\TestCase;

abstract class BaseIntegrationTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [WhatsAppProvider::class];
    }
}
