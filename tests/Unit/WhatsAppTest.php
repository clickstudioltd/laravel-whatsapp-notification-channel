<?php

namespace NotificationChannels\WhatsApp\Tests\Unit;

use Illuminate\Contracts\Events\Dispatcher;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\WhatsApp\Exceptions\CouldNotSendNotification;
use NotificationChannels\WhatsApp\WhatsApp;
use NotificationChannels\WhatsApp\WhatsAppClient;
use NotificationChannels\WhatsApp\WhatsAppConfig;
use NotificationChannels\WhatsApp\Messages\WhatsAppMessage;
use NotificationChannels\WhatsApp\Messages\WhatsAppTemplateMessage;

class WhatsAppTest extends MockeryTestCase
{
    /** @var WhatsAppClient */
    protected $whatsAppClient;

    /** @var WhatsAppConfig */
    protected $config;

    /** @var Dispatcher */
    protected $dispatcher;

    /** @var WhatsApp */
    protected $whatsApp;

    public function setUp(): void
    {
        parent::setUp();

        $this->whatsAppClient = Mockery::mock(WhatsAppClient::class);
        $this->config = Mockery::mock(WhatsAppConfig::class);
        $this->dispatcher = Mockery::mock(Dispatcher::class);
        $this->whatsApp = new WhatsApp($this->whatsAppClient, $this->config);
    }

    /** @test */
    public function it_can_send_a_template_message_to_whatsapp()
    {
        $message = new WhatsAppTemplateMessage([
            'name' => 'sample_template'
        ]);

        $this->config
            ->shouldReceive('getFrom')
            ->once()
            ->andReturn('+1111111111');

        $this->config
            ->shouldReceive('getDebugTo')
            ->once()
            ->andReturn(null);

        $this->whatsAppClient
            ->shouldReceive('enqueueMessage')
            ->once()
            ->with([
                'from' => '+1111111111',
                'to' => '+22222222222',
                'type' => 'template',
                'template' => [
                    'name' => 'sample_template'
                ]
            ], [])
            ->andReturn([]);

        $this->whatsApp->sendMessage($message, '+22222222222');
    }

    /** @test */
    public function it_will_throw_an_exception_in_case_of_a_missing_from_number()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Notification was not sent. Missing `from` number.');

        $this->config
            ->shouldReceive('getFrom')
            ->once()
            ->andReturn(null);

        $this->config
            ->shouldReceive('getDebugTo')
            ->once()
            ->andReturn(null);

        $templateMessage = new WhatsAppTemplateMessage([
            'name' => 'sample_template'
        ]);

        $this->whatsApp->sendMessage($templateMessage, null);
    }

    /** @test */
    public function it_will_throw_an_exception_in_case_of_an_unrecognized_message_object()
    {
        $this->expectException(CouldNotSendNotification::class);
        $this->expectExceptionMessage('Notification was not sent. Message object class');

        $this->whatsApp->sendMessage(new InvalidMessage(), null);
    }

    /** @test */
    public function it_should_use_universal_to()
    {
        $debugTo = '+1222222222';

        $this->config
            ->shouldReceive('getFrom')
            ->once()
            ->andReturn('+1234567890');

        $this->config
            ->shouldReceive('getDebugTo')
            ->once()
            ->andReturn($debugTo);

        $this->whatsAppClient
            ->shouldReceive('enqueueMessage')
            ->once()
            ->with([
                'from' => '+1234567890',
                'to' => $debugTo,
                'type' => 'template',
                'template' => [
                    'name' => 'sample_template'
                ]
            ], [])
            ->andReturn([]);

        $message = new WhatsAppTemplateMessage([
            'name' => 'sample_template'
        ]);

        $this->whatsApp->sendMessage($message, '+1111111111');
    }
}

class InvalidMessage extends WhatsAppMessage
{
}
