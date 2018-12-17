<?php
namespace App\Validator;
use App\Classes\AppResponse;
use App\Errors\ErrorWithCode;
use \Illuminate\Validation\Validator;

/**
 * Error code can be specified in rules as error_code,mycode where mycode is the value of $code to be used.
 * Class ValidatorWithCode
 * @package App\Validator
 */
class ValidatorWithCode extends Validator{
    protected function addFailure($attribute, $rule, $parameters)
    {
        $message = $this->getMessage($attribute, $rule);
        $message = $this->makeReplacements($message, $attribute, $rule, $parameters);
        $code = -1;
        $index = array_search('error_code',$parameters);
        if($index !== false && count($parameters)>($index+1)){
            $code = $parameters[$index+1];
        }

        AppResponse::addErrorInBag($this->messages,$attribute,new ErrorWithCode($message,$code));
    }
}