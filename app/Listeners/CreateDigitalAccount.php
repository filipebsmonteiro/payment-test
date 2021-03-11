<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Services\Interfaces\AccountsInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateDigitalAccount
{
    private $accountService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->accountService = resolve(AccountsInterface::class);
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $this->accountService->store($event->accountType, $event->userId, true);
    }
}
