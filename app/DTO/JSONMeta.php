<?php

namespace App\DTO;

use App\Classes\Helper;
use Illuminate\Support\Collection;

class JSONMeta implements \JsonSerializable {
    /**
     * @var array
     */
    public $setting;

    public function __construct($data = null, $init = false)
    {
        if($init) {
            $this->initFromJson($data);
        }
    }

    public function &__get($name)
    {
        return Helper::getKeyValue($this->setting,$name);
    }
    
    public function __set($name, $value)
    {
        $this->setting[$name] = $value;
    }

    protected function toDecoded($jsonOrArr) {
        if(is_string($jsonOrArr)) {
            return json_decode($jsonOrArr,true);
        }

        return $jsonOrArr;
    }
	
	function __isset($name)
    {
        return isset($this->setting[$name]);
    }

    /**
     * @return array
     */
    protected function keysRequiringProcessing($json) {
        return [];
    }

    /**
     * @param string|array $json
     * @return void
     */
    public function initFromJson($json) {
        $json = $this->toDecoded($json);
        $this->setting = $this->toDecoded($json);
        $keysRequiringProcessing = $this->keysRequiringProcessing($json);
        foreach ($keysRequiringProcessing as $key => $suggestedDefault) {
            $this->setting[$key] = $this->getValueFor($key, $suggestedDefault);
        }
    }

    protected function getValueFor($key, $suggestedDefault) {
        return Helper::getKeyValue($this->setting,$key,$suggestedDefault);
    }

    public function jsonSerialize()
    {
        return $this->setting;
    }
}