<?php

namespace App\Listeners\site;

use App\Events\site\registerUserSiteByEmailEvent;
use App\Mail\site\registerUserSiteByEmail;
use Illuminate\Support\Facades\Mail;

class registerUserSiteByEmailListener
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
     * Handle the event.
     *
     * @param  registerUserSiteByEmailEvent  $event
     * @return void
     */
    public function handle(registerUserSiteByEmailEvent $event)
    {
        // Ex1: Access the comment using $event->userSite...
        // Ex2: Access the comment using $event->getUserSite()...

        // Registring log commented post
        //Log::info($event->getUserSite());
        //Teste view email
        //new registerUserSiteByEmail($event->getUserSite(), $event->getStore());
        // Sending email verification account
        Mail::send(new registerUserSiteByEmail($event->getUserSite(), $event->getStore()));
    }
}
