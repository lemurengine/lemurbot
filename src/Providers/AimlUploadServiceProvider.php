<?php

namespace LemurEngine\LemurBot\Providers;

use LemurEngine\LemurBot\Services\AimlUploadService;
use Illuminate\Support\ServiceProvider;

class AimlUploadServiceProvider extends ServiceProvider
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
        $this->app->singleton(AimlUploadService::class, function () {
            return new AimlUploadService();
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
