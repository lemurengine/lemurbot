<?php

namespace LemurEngine\LemurBot\Providers;

use LemurEngine\LemurBot\Services\WordSpellingUploadService;
use Illuminate\Support\ServiceProvider;

class WordSpellingUploadServiceProvider extends ServiceProvider
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
        $this->app->singleton(WordSpellingUploadService::class, function () {
            return new WordSpellingUploadService();
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
