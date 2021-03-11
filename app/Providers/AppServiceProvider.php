<?php

namespace App\Providers;

use App\Services\Implementations\AccountsService;
use App\Services\Implementations\TransactionsService;
use App\Services\Implementations\UsersService;
use App\Services\Interfaces\AccountsInterface;
use App\Services\Interfaces\TransactionsInterface;
use App\Services\Interfaces\UsersInterface;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AccountsInterface::class, AccountsService::class);
        $this->app->bind(UsersInterface::class, UsersService::class);
        $this->app->bind(TransactionsInterface::class, TransactionsService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('pt-BR');
        //
    }
}
