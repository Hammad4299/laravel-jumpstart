<?php
namespace App\Errors;

class ErrorWithCode implements IError {
    /**
     * @var string
     */
    protected $message;
    protected $code;

    public function __construct($message, $code = -1)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function jsonSerialize() {
        return $this->getErrorData();
    }

    function __toString()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    function getErrorData()
    {
        return [
            'message'=>$this->message,
            'code'=>$this->code
        ];
    }
}