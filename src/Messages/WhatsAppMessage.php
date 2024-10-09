<?php

namespace NotificationChannels\WhatsApp\Messages;

abstract class WhatsAppMessage
{
    /**
     * HTTP client request options.
     *
     * @var array
     */
    protected $options;

    /**
     * The phone number the message should be sent from.
     *
     * @var string
     */
    protected $from;

    /**
     * The phone number the message should be sent to. (Override the existing To number otherwise optional.)
     *
     * @var string
     */
    protected $to;

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
     * Set the message specific HTTP client request options.
     *
     * @param  string $from
     * @return $this
     */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get the message specific HTTP client request options.
     *
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
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
