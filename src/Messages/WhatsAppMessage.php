<?php

namespace NotificationChannels\WhatsApp\Messages;

abstract class WhatsAppMessage
{
    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    public $from;

    /**
     * The phone number the message should be sent to. (Override the existing To number otherwise optional.)
     *
     * @var string
     */
    public $to;

    /**
     * Create a message object.
     *
     * @param array $template
     * @return static
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * Set the phone number the message should be sent from.
     *
     * @param  string $from
     * @return $this
     */
    public function from(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get the from address.
     *
     * @return string|null
     */
    public function getFrom(): ?string
    {
        return $this->from;
    }

    /**
     * Set the phone number the message should be sent to.
     *
     * @param  string  $to
     * @return $this
     */
    public function to(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get the to address.
     *
     * @return string|null
     */
    public function getTo(): ?string
    {
        return $this->to;
    }
}
