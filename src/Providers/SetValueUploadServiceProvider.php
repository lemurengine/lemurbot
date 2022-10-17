<?php

namespace LemurEngine\LemurBot\Providers;

use LemurEngine\LemurBot\Services\SetValueUploadService;
use Illuminate\Support\ServiceProvider;

class SetValueUploadServiceProvider extends ServiceProvider
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
        $this->app->singleton(SetValueUploadService::class, function () {
            return new SetValueUploadService();
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
