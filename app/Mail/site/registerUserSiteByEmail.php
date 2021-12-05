<?php

namespace App\Mail\site;

use App\mdStores;
use App\UserSite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class registerUserSiteByEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $userSite;
    private $store;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserSite $userSite, mdStores $store)
    {
        $this->userSite = $userSite;
        $this->store = $store;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject('Seja bem vindo ao Lietoo! Confirme Seu Email');
        $this->to( $this->userSite->email, $this->userSite->name);

        return $this->markdown('mail.site.registerUserSiteByEmail', [
            'appUrl'        => env('APP_URL'),
            'store'         => $this->store,
            'userSite'      => $this->userSite
        ]);
    }
}
