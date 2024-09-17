<?php

namespace NotificationChannels\WhatsApp\Tests\Unit;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\WhatsApp\Exceptions\CouldNotSendNotification;
use NotificationChannels\WhatsApp\Exceptions\HttpException;
use NotificationChannels\WhatsApp\WhatsApp;
use NotificationChannels\WhatsApp\WhatsAppChannel;
use NotificationChannels\WhatsApp\WhatsAppConfig;
use NotificationChannels\WhatsApp\Messages\WhatsAppTemplateMessage;

class WhatsAppChannelTest extends MockeryTestCase
{
    /** @var WhatsApp */
    protected $whatsapp;

    /** @var Dispatcher */
    protected $dispatcher;

    /** @var WhatsAppChannel */
    protected $channel;

    public function setUp(): void
    {
        parent::setUp();

        $this->whatsapp = Mockery::mock(WhatsApp::class);
        $this->dispatcher = Mockery::mock(Dispatcher::class);

        $this->channel = new WhatsAppChannel($this->whatsapp, $this->dispatcher);
    }

    /** @test */
    public function it_will_not_send_a_message_without_known_receiver()
    {
        $notifiable = new Notifiable();

        $notification = Mockery::mock(Notification::class);
        $notification->shouldReceive('toWhatsApp')->andReturn([]);

        $this->whatsapp->config = new WhatsAppConfig([
            'ignored_error_codes' => [],
        ]);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->atLeast()->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->expectException(CouldNotSendNotification::class);

        $result = $this->channel->send($notifiable, $notification);

        $this->assertNull($result);
    }

    /** @test */
    public function it_will_send_a_template_message_to_the_result_of_the_route_method_of_the_notifiable()
    {
        $notifiable = new NotifiableWithMethod();

        $message = new WhatsAppTemplateMessage([
            'name' => 'sample_template'
        ]);

        $notification = Mockery::mock(Notification::class);
        $notification->shouldReceive('toWhatsApp')->andReturn($message);

        $this->whatsapp
            ->shouldReceive('sendMessage')
            ->atLeast()->once()
            ->with($message, '+1111111111');

        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_fire_an_event_in_case_of_an_invalid_message()
    {
        $this->whatsapp->config = new WhatsAppConfig([
            'ignored_error_codes' => [],
        ]);

        $notifiable = new NotifiableWithAttribute();

        $notification = Mockery::mock(Notification::class);

        // Invalid message.
        $notification->shouldReceive('toWhatsApp')->andReturn(-1);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->atLeast()
            ->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->expectException(CouldNotSendNotification::class);

        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_ignore_specific_error_codes()
    {
        $this->whatsapp->config = new WhatsAppConfig([
            'ignored_error_codes' => [
                400,
            ],
        ]);

        $message = new WhatsAppTemplateMessage([
            'name' => 'sample_template'
        ]);

        $this->whatsapp
            ->shouldReceive('sendMessage')
            ->with($message, '+22222222222')
            ->andThrow(new HttpException('error', 400));

        $notifiable = new NotifiableWithAttribute();

        $notification = Mockery::mock(Notification::class);
        $notification->shouldReceive('toWhatsApp')->andReturn($message);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->atLeast()
            ->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_rethrow_non_ignored_error_codes()
    {
        $this->whatsapp->config = new WhatsAppConfig([
            'ignored_error_codes' => [
                55555,
            ],
        ]);

        $message = new WhatsAppTemplateMessage([
            'name' => 'sample_template'
        ]);

        $this->whatsapp
            ->shouldReceive('sendMessage')
            ->with($message, '+22222222222')
            ->andThrow(new HttpException('error', 400));

        $notifiable = new NotifiableWithAttribute();

        $notification = Mockery::mock(Notification::class);
        $notification->shouldReceive('toWhatsApp')->andReturn($message);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->atLeast()
            ->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->expectException(HttpException::class);

        $this->channel->send($notifiable, $notification);
    }

    /** @test */
    public function it_will_ignore_all_error_codes()
    {
        $this->whatsapp->config = new WhatsAppConfig([
            'ignored_error_codes' => ['*'],
        ]);

        $this->whatsapp
            ->shouldReceive('sendMessage')
            ->andThrow(new HttpException('error', 400));

        $notifiable = new NotifiableWithAttribute();

        $notification = Mockery::mock(Notification::class);
        $notification->shouldReceive('toWhatsApp')->andReturn([]);

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->atLeast()
            ->once()
            ->with(Mockery::type(NotificationFailed::class));

        $this->channel->send($notifiable, $notification);
    }
}

class Notifiable
{
    public $phone_number = null;

    public function routeNotificationFor() {}
}

class NotifiableWithMethod
{
    public function routeNotificationFor()
    {
        return '+1111111111';
    }
}

class NotifiableWithAttribute
{
    public $phone_number = '+22222222222';

    public function routeNotificationFor() {}
}
