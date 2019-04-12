<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class SampleEvent
{
    use SerializesModels;

    public $eventData1;

    public function __construct($eventData1)
    {
        $this->eventData1 = $eventData1;
    }
}