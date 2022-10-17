<?php

namespace LemurEngine\LemurBot\Providers;

use Illuminate\Support\ServiceProvider;
use LemurEngine\LemurBot\Services\LemurInstallBotService;

class LemurInstallBotServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LemurInstallBotService::class, function () {
            return new LemurInstallBotService();
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
