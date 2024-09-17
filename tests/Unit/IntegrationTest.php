<?php

namespace NotificationChannels\WhatsApp\Tests\Unit;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Notification;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\WhatsApp\WhatsApp;
use NotificationChannels\WhatsApp\WhatsAppChannel;
use NotificationChannels\WhatsApp\WhatsAppClient;
use NotificationChannels\WhatsApp\WhatsAppConfig;
use NotificationChannels\WhatsApp\Messages\WhatsAppTemplateMessage;

class IntegrationTest extends MockeryTestCase
{
    /** @var WhatsAppClient */
    protected $whatsappClient;

    /** @var Notification */
    protected $notification;

    /** @var Dispatcher */
    protected $events;

    public function setUp(): void
    {
        parent::setUp();

        $this->whatsappClient = Mockery::mock(WhatsAppClient::class);
        $this->notification = Mockery::mock(Notification::class);
        $this->events = Mockery::mock(Dispatcher::class);
    }

    /** @test */
    public function it_can_send_a_template_message()
    {
        $this->whatsappClient
            ->shouldReceive('enqueueMessage')
            ->once()
            ->with([
                'from' => '+11111111111',
                'to' => '+22222222222',
                'type' => 'template',
                'template' => [
                    'name' => 'sample_template'
                ],
            ])
            ->andReturn([]);

        $config = new WhatsAppConfig([
            'from' => '+11111111111',
        ]);
        $whatsapp = new WhatsApp($this->whatsappClient, $config);
        $channel = new WhatsAppChannel($whatsapp, $this->events);
        $message = new WhatsAppTemplateMessage([
            'name' => 'sample_template'
        ]);

        $this->notification
            ->shouldReceive('toWhatsApp')
            ->andReturn($message);

        $channel->send(new NotifiableWithAttribute(), $this->notification);
    }
}