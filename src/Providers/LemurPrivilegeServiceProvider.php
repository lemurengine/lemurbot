<?php

namespace LemurEngine\LemurBot\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use LemurEngine\LemurBot\Services\LemurPrivilegeService;

class LemurPrivilegeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LemurPrivilegeService::class, function () {
            return new LemurPrivilegeService();
        });

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
