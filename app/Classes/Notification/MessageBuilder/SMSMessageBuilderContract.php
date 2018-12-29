<?php

namespace App\Classes\Notification\MessageBuilder;

use App\Classes\Notification\Message\TextMessage;

interface SMSMessageBuilderContract extends MessageBuilderContract {
    /**
     * @return TextMessage
     */
    function buildSmsMessage();
}