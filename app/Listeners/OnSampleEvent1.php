<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\SampleEvent;

class OnSampleEvent1 implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param SampleEvent $event
     * @return void
     */
    public function handle(SampleEvent $event)
    {
        
    }
}