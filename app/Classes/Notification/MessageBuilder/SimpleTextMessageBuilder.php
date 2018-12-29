<?php

namespace App\Classes\Notification\MessageBuilder;

use App\Classes\Notification\Message\TextMessage;

class SimpleTextMessageBuilder extends AbstractMessageBuilder implements SMSMessageBuilderContract {
    protected $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    function buildSmsMessage()
    {
        return new TextMessage($this->makeReplacements($this->text));
    }
}