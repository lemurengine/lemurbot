<?php

namespace LemurEngine\LemurBot\Providers;

use LemurEngine\LemurBot\Services\BotPropertyUploadService;
use Illuminate\Support\ServiceProvider;

class BotPropertyUploadServiceProvider extends ServiceProvider
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
        $this->app->singleton(BotPropertyUploadService::class, function () {
            return new BotPropertyUploadService();
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
