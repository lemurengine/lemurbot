<?php

namespace LemurEngine\LemurBot\Providers;

use LemurEngine\LemurBot\Services\MapValueUploadService;
use Illuminate\Support\ServiceProvider;

class MapValueUploadServiceProvider extends ServiceProvider
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
        $this->app->singleton(MapValueUploadService::class, function () {
            return new MapValueUploadService();
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
