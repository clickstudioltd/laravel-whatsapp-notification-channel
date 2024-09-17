<?php

namespace NotificationChannels\WhatsApp\Messages;

class WhatsAppTemplateMessage extends WhatsAppMessage
{
    /**
     * The message template.
     *
     * @var array
     */
    public $template;

    /**
     * Create a new message instance.
     *
     * @param  array $template
     */
    public function __construct(array $template = [])
    {
        $this->template = $template;
    }

    /**
     * Set the message template.
     *
     * @param  array $template
     * @return $this
     */
    public function template(array $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get the message template.
     *
     * @return array|null
     */
    public function getTemplate(): ?array
    {
        return $this->template;
    }

    /**
     * Get the from address of this message.
     *
     * @return null|string
     */
    public function getFrom(): ?string
    {
        if ($this->from) {
            return $this->from;
        }

        return null;
    }
}
