<?php

namespace LemurEngine\LemurBot\Providers\Install;

use Illuminate\Support\ServiceProvider;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAdminService;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAimlService;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAllService;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAppService;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallBotService;
use LemurEngine\LemurBot\Services\Install\LemurBotUpgradeService;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallWordListsService;

class LemurBotInstallAllServiceProvider extends ServiceProvider
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
        $this->app->singleton(LemurBotInstallAllService::class, function () {
            return new LemurBotInstallAllService(
                new LemurBotInstallAppService(),
                new LemurBotInstallAdminService(),
                new LemurBotUpgradeService(),
                new LemurBotInstallWordListsService(),
                new LemurBotInstallBotService(),
                new LemurBotInstallAimlService()
            );
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
