<?php

namespace App\Events\site;

use App\mdStores;
use App\UserSite;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class registerUserSiteByEmailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $userSite;
    private $store;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(UserSite $userSite, mdStores $store)
    {
        $this->userSite = $userSite;
        $this->store = $store;
    }

    /**
     * UserSite
     *
     * @return \App\UserSite $userSite
     */
    public function getUserSite(): UserSite
    {
        return $this->userSite;
    }

    /**
     * store
     *
     * @return \App\mdStores $store
     */
    public function getStore(): mdStores
    {
        return $this->store;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
