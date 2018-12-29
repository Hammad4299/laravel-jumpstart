<?php

namespace App\Classes\Notification\Message;

class TextMessage {
    /**
     * @var string
     */
    protected $message;

    public function __construct($msg = '')
    {
        $this->message = $msg;
    }

    /**
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * @param $msg string
     */
    public function setMessage($msg) {
        $this->message = $msg;
    }
}