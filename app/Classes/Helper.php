<?php
namespace App\Classes;

use Illuminate\Database\Eloquent\Model;

class Helper
{
       
    public static function &getKeyValue(&$arrOrObj, $key, $default = null) {
        $val = &$default;
        if($arrOrObj!==null) {
            if (is_array($arrOrObj)) {
                if(array_key_exists($key, $arrOrObj)) {
                    $val = &$arrOrObj[$key];
                }
            } else if ($arrOrObj instanceOf Model) {
                if(array_key_exists($key,$arrOrObj->getAttributes())) {
                    $val = &$arrOrObj->getAttributes(){$key};
                }
            } else {
                if(property_exists($arrOrObj,$key)) {
                    $val = &$arrOrObj->{$key};
                }
            }
        }
        return $val;
    }

    public static function &defaultOnEmptyKey(&$arrOrObj, $key, $default = null) {
        $val = &$default;
        if($arrOrObj!==null) {
            if (is_array($arrOrObj)) {
                if(array_key_exists($key, $arrOrObj) && !empty($arrOrObj[$key])) {
                    $val = &$arrOrObj[$key];
                }
            } else if ($arrOrObj instanceOf Model) {
                if(array_key_exists($key,$arrOrObj->getAttributes()) && !empty($arrOrObj->{$key})) {
                    $val = &$arrOrObj->getAttributes(){$key};
                }
            } else {
                if(property_exists($arrOrObj,$key) && !empty($arrOrObj->{$key})) {
                    $val = &$arrOrObj->{$key};
                }
            }
        }
        return $val;
    }

    public static function &defaultIfKeyNotSet(&$arrOrObj, $key, $default = null) {
        $val = &$default;
        if($arrOrObj!==null) {
            if (is_array($arrOrObj)) {
                if(isset($arrOrObj[$key])) {
                    $val = &$arrOrObj[$key];
                }
            } else if ($arrOrObj instanceOf Model) {
                if(array_key_exists($key,$arrOrObj->getAttributes()) && isset($arrOrObj->{$key})) {
                    $val = &$arrOrObj->getAttributes(){$key};
                }
            } else {
                if(isset($arrOrObj[$key])) {
                    $val = &$arrOrObj->{$key};
                }
            }
        }
        return $val;
    }

    /**
     * Replaces  multiple / with single / and replaces multiple \ with single /
     * @param $path
     * @return null|string|string[]
     */
    public static function fixUri($path){
        return preg_replace('#/+#','/',str_replace('\\','/',$path));
    }
}