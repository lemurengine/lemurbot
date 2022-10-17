<?php

namespace LemurEngine\LemurBot\Providers;

use Illuminate\Support\ServiceProvider;
use LemurEngine\LemurBot\Services\LemurInstallAimlService;

class LemurInstallAimlServiceProvider extends ServiceProvider
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
        $this->app->singleton(LemurInstallAimlService::class, function () {
            return new LemurInstallAimlService();
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
