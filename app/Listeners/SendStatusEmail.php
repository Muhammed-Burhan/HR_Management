<?php

namespace App\Listeners;

use App\Mail\DeviceStatus_Email;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendStatusEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @param  Device_Status_Changed $event
     */
    public function handle(object $event)
    {
        Mail::to($event->user)->send(new DeviceStatus_Email($event->user,$event->device));
    }
}
