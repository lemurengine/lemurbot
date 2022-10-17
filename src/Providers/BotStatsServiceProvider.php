<?php

namespace LemurEngine\LemurBot\Providers;

use LemurEngine\LemurBot\Services\BotStatsService;
use Illuminate\Support\ServiceProvider;

class BotStatsServiceProvider extends ServiceProvider
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
        $this->app->singleton(BotStatsService::class, function () {
            return new BotStatsService();
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
