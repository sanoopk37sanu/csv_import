<?php

namespace App\Listeners;

use App\Events\UserImported;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class SendWelcomeEmail
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
     */
    public function handle(UserImported $event): void
    {
        Mail::to($event->user->email)->send(new WelcomeMail($event->user));
    }
}
