<?php

namespace App\DTO;

use App\Classes\Helper;
use App\Models\KioskThemeSetting;
use Illuminate\Support\Collection;

class GeneralMetaDTO implements \JsonSerializable {
    /**
     * @var array
     */
    public $setting;

    public function &__get($name)
    {
        return Helper::getKeyValue($this->setting,$name);
    }
    
    public function __set($name, $value)
    {
        $this->setting[$name] = $value;
    }

    /**
     * @return array
     */
    protected function defaults($json) {
        return [];
    }

    protected function toDecoded($jsonOrArr) {
        if(is_string($jsonOrArr)) {
            return json_decode($jsonOrArr,true);
        }

        return $jsonOrArr;
    }

    /**
     * @param string|array $json
     * @return GeneralMetaDTO
     */
    public function initFromJson($json) {
        $json = $this->toDecoded($json);
        $this->setting = $this->toDecoded($json);
        $defaults = $this->defaults($json);
        foreach ($defaults as $key => $value) {
            $this->setting[$key] = Helper::getKeyValue($this->setting,$key,$defaults[$key]);
        }
    }

    public function jsonSerialize()
    {
        return $this->setting;
    }
}