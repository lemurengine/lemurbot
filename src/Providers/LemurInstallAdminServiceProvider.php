<?php

namespace LemurEngine\LemurBot\Providers;

use Illuminate\Support\ServiceProvider;
use LemurEngine\LemurBot\Services\LemurInstallAdminService;

class LemurInstallAdminServiceProvider extends ServiceProvider
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
        $this->app->singleton(LemurInstallAdminService::class, function () {
            return new LemurInstallAdminService();
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
