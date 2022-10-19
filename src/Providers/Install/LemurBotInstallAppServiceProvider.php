<?php

namespace LemurEngine\LemurBot\Providers\Install;

use Illuminate\Support\ServiceProvider;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAppService;

class LemurBotInstallAppServiceProvider extends ServiceProvider
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
        $this->app->singleton(LemurBotInstallAppService::class, function () {
            return new LemurBotInstallAppService();
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
