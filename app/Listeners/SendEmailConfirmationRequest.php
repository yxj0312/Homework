<?php

namespace App\Listeners;

use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailConfirmationRequest
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event. Move to RegisterController
     *
     * @param  Registered  $event
     * @return void
     */
    // public function handle(Registered $event)
    // {
    //     Mail::to($event->user)->send(new PleaseConfirmYourEmail($event->user));
    // }
}
