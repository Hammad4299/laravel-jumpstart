<?php
namespace App\Errors;

class Error implements IError {
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function jsonSerialize() {
        return $this->message;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    function getErrorData()
    {
        return $this->message;
    }
}