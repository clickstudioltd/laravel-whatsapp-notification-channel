<?php

declare(strict_types=1);

namespace NotificationChannels\WhatsApp\Tests\Integration;

use NotificationChannels\WhatsApp\WhatsAppChannel;

class WhatsAppProviderTest extends BaseIntegrationTest
{
    public function testThatApplicationCreatesChannelWithConfig()
    {
        $this->app['config']->set('whatsapp-notification-channel.api_key', 'abcd');

        $this->assertInstanceOf(WhatsAppChannel::class, $this->app->get(WhatsAppChannel::class));
    }
}
