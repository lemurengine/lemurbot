<?php

namespace LemurEngine\LemurBot\Providers;

use Illuminate\Support\ServiceProvider;
use LemurEngine\LemurBot\Services\LemurInstallAppService;

class LemurInstallAppServiceProvider extends ServiceProvider
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
        $this->app->singleton(LemurInstallAppService::class, function () {
            return new LemurInstallAppService();
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
