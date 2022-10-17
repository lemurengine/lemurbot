<?php

namespace LemurEngine\LemurBot\Providers;

use LemurEngine\LemurBot\Services\WordTransformationUploadService;
use Illuminate\Support\ServiceProvider;

class WordTransformationUploadServiceProvider extends ServiceProvider
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
        $this->app->singleton(WordTransformationUploadService::class, function () {
            return new WordTransformationUploadService();
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
