<?php

namespace App\Classes\Notification\MessageBuilder;

interface MessageBuilderContract {
    /**
     * Allow specific sender to incorporate its own specific replacement instead of application level e.g. if using sendgrid template, we can make replacement such that it can be delegated to sendgrid
     * @param Dictionary<string,string> $replacements
     * @return void
     */
    function setPreferredReplacements($replacements);
}