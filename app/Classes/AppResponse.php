<?php

namespace App\Classes;

use App\Errors\IError;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Validator;

class AppResponse implements \JsonSerializable
{
    public $data;
    public $message;

    /**
     * @var integer|null
     */
    protected $statusCode;

    /**
     * @var bool
     */
    protected $status;

    /**
     * @var MessageBag
     */
    public $errors;

    /**
     * @var bool
     */
    public $reload;
    /**
     * @var string|null
     */
    public $redirectUrl;

    public function mergeErrors(MessageBag $with){
        foreach ($with->toArray() as $key => $errors){
            foreach ($errors as $error){
                $this->addError($key, $error);
            }
        }
    }

    /**
     * @param string $field
     * @param IError|string $error
     */
    public function addError($field, $error) {
        self::addErrorInBag($this->errors,$field, $error);
        $this->setStatus(false);
    }

    /**
     * @param MessageBag $bag
     * @param string $field
     * @param IError|string $error
     */
    public static function addErrorInBag(MessageBag $bag, $field, $error){
        $bag->merge([$field=>is_string($error) ? [$error] : $error->getErrorData()]);
    }

    public function addErrorsFromValidator(Validator $validator){
        $this->addErrorsFromMessageBag($validator->errors());
    }


    public function addErrorsFromMessageBag(MessageBag $errors) {
        foreach ($errors->toArray() as $key => $messages){
            foreach ($messages as $message){
                $this->addError($key, $message);
            }
        }
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function setStatusCode($statusCode){
        $this->statusCode = $statusCode;
    }

    public function clearErrors() {
        $this->errors = new MessageBag();
    }

    public function getStatus() {
        return $this->status;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function __construct($status = false, $data = null)
    {
        $this->data = $data;
        $this->setStatus($status);
        $this->redirectUrl = null;
        $this->clearErrors();
    }

    public function jsonSerialize() {
        $out = array();
        $out['data'] = $this->data;
        $out['status'] = $this->getStatus();
        $out['statusCode'] = $this->getStatusCode();
        $out['reload'] = $this->reload;
        $out['redirectUrl'] = $this->redirectUrl;
        $out['message'] = $this->message;
        $out['errors'] = $this->errors == null || count($this->errors)==0 ? null : $this->errors;
        return $out;
    }
}