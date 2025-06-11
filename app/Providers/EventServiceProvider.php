<?php

namespace App\Providers;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;                 //event login
use App\Listeners\LogFailedLoginAttempt;
use App\Listeners\LogSuccessfulLogin;              //listener login sukses
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Failed::class => [
            LogFailedLoginAttempt::class,
        ],

        Login::class => [                              // event login sukses
            LogSuccessfulLogin::class,                 // listener login sukses
        ],
    ];

    public function boot(): void
    {
        //
    }
}
