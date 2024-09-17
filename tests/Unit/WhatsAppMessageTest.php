<?php

namespace NotificationChannels\WhatsApp\Tests\Unit;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use NotificationChannels\WhatsApp\WhatsAppMessage;

abstract class WhatsAppMessageTest extends MockeryTestCase
{
    /** @var WhatsAppMessage */
    protected $message;

    /** @test */
    abstract public function it_can_accept_a_template_when_constructing_a_message();

    /** @test */
    public function it_can_set_the_from()
    {
        $this->message->from('+1234567890');

        $this->assertEquals('+1234567890', $this->message->from);
    }

    /** @test */
    public function it_can_return_the_from_using_getter()
    {
        $this->message->from('+1234567890');

        $this->assertEquals('+1234567890', $this->message->getFrom());
    }

    /** @test */
    public function it_can_set_the_to()
    {
        $this->message->to('+1234567890');

        $this->assertEquals('+1234567890', $this->message->to);
    }

    /** @test */
    public function it_can_return_the_to_using_getter()
    {
        $this->message->to('+1234567890');

        $this->assertEquals('+1234567890', $this->message->getTo());
    }
}
