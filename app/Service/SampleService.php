<?php

namespace App\Service;

use App\Events\SampleEvent;
use App\Classes\AppResponse;


class SampleService {
    function doSampleWork($data, $user) {
        $resp = new AppResponse(true);
        if($data) {
            event(new SampleEvent($data));
        }
        return $resp;
    }
}