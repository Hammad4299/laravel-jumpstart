<?php
namespace App\Classes;

use App\Storage\FileHandle;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\Paginator as SimplePaginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as LengthPaginator;

class Helper
{
    /**
     * @return Collection|null
     */
    public static function toCollection($data) {
        if(is_array($data)) {
            return new Collection($data);
        } else if($data instanceOf Collection) {
            return $data;
        } else if($data instanceOf Paginator) {
            return self::toCollection($data->items());
        } else if ($data !== null) {
            return new Collection([$data]);
        }
        return null;
    }

    /**
     * Get data rows from a query as a simple collection.
     * @param Collection|Paginator|array|mixed $data
     * @return Collection|null
     */
    public static function getItemsFromSelectionQueryResults($data) {
        if($data instanceOf Paginator) {
            return self::toCollection($data->items());
        } else if($data instanceOf Collection || is_array($data)) {
            return self::toCollection($data);
        } else {
            return null;
        }
    }

    /**
     * return $items in same Class as represented by $data.
     * @param Collection|Paginator|array|mixed $data (actual results returned by querybuilder array, paginator, collection)
     * @param Collection|array $items
     * @return Collection|Paginator|null
     */
    public static function rebuildQueryResultsWithNewItems($data, $items) {
        if($data instanceOf Paginator) {
            $toRet = new SimplePaginator($items,$data->perPage(),$data->currentPage());
            if($data instanceOf LengthAwarePaginator) {
                $toRet = new LengthPaginator($items,$data->total(),$data->perPage(), $data->currentPage());
            }
            return $toRet;
        } else if($data instanceOf Collection || is_array($data)) {
            return self::toCollection($items);
        } else {
            return null;
        }
    }

    public static function toDecoded($jsonOrArr, $assoc_arr = true) {
        if(is_string($jsonOrArr)) {
            return json_decode($jsonOrArr,$assoc_arr);
        }

        return $jsonOrArr;
    }

    /**
     * Sends file represented by handle to download via nginx
     * @param FileHandle $handle
     * @return void
     */
    public static function sendFileDownloadResponse($handle, $filename = null) {
        if($filename===null) {
            $filename = pathinfo($handle->getRelPath(),PATHINFO_FILENAME);
            $extension = pathinfo($handle->getRelPath(),PATHINFO_EXTENSION);
            $filename = "$filename.$extension";
        }
        header("Content-Disposition: attachment; filename=$filename");
        header("X-Accel-Redirect: ".$handle->getInternalRedirectUrl());
        return response('ok');
    }

    public static function utcOffsetToTimezone($offsetMinutes) {
        $sign = '+';
        $hour = (abs($offsetMinutes)/60).'';
        $minutes = (abs($offsetMinutes)%60).'';
        if(strlen($hour)===1) {
            $hour = '0'.$hour;
        }
        if(strlen($minutes)===1) {
            $minutes = '0'.$minutes;
        }
        if($offsetMinutes<0) {
            $sign = '-';
        }
        return "$sign$hour:$minutes";
    }

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