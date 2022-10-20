<?php

namespace LemurEngine\LemurBot\Providers\Upgrade;

use Illuminate\Support\ServiceProvider;
use LemurEngine\LemurBot\Services\Install\LemurBotUpgradeService;
use LemurEngine\LemurBot\Services\LemurInstallBotService;

class LemurBotUpgradeServiceProvider extends ServiceProvider
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
        $this->app->singleton(LemurBotUpgradeService::class, function () {
            return new LemurBotUpgradeService();
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
