<?php

namespace App\Classes\Notification\MessageBuilder;

class AbstractMessageBuilder implements MessageBuilderContract {
    protected $preferredReplacements;

    protected function getReplacements() {
        return [];
    }

    public static function performReplacements($subject, $replacements) {
        //TODO
        return $subject;
    }

    protected function makeReplacements($subject) {
        $replacements = array_merge($this->getReplacements(), $this->preferredReplacements);
        return self::performReplacements($subject, $replacements);
    }

    /**
     * Allow specific sender to incorporate its own specific replacement instead of application level e.g. if using sendgrid template, we can make replacement such that it can be delegated to sendgrid
     * @param Dictionary<string,string> $replacements
     * @return void
     */
    function setPreferredReplacements($replacements) {
        $this->preferredReplacements = $replacements;
    }
}