<?php

namespace LemurEngine\LemurBot\Providers\Install;

use Illuminate\Support\ServiceProvider;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAimlService;

class LemurBotInstallAimlServiceProvider extends ServiceProvider
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
        $this->app->singleton(LemurBotInstallAimlService::class, function () {
            return new LemurBotInstallAimlService();
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
