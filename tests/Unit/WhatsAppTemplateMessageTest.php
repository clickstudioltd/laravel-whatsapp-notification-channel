<?php

namespace NotificationChannels\WhatsApp\Tests\Unit;

use NotificationChannels\WhatsApp\Messages\WhatsAppTemplateMessage;

class WhatsAppTemplateMessageTest extends WhatsAppMessageTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->message = new WhatsAppTemplateMessage([
            'name' => 'sample_template'
        ]);
    }

    /** @test */
    public function it_can_accept_a_template_when_constructing_a_message()
    {
        $message = new WhatsAppTemplateMessage([
            'name' => 'sample_template'
        ]);

        $this->assertEquals([
            'name' => 'sample_template'
        ], $message->template);
    }

    /** @test */
    public function it_can_get_set_the_template()
    {
        $this->message->template([]);

        $this->assertEquals([], $this->message->getTemplate());
    }
}
