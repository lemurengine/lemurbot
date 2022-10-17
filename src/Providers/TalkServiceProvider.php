<?php

namespace LemurEngine\LemurBot\Providers;

use LemurEngine\LemurBot\Classes\AimlMatcher;
use LemurEngine\LemurBot\Classes\AimlParser;
use LemurEngine\LemurBot\Services\TalkService;
use Illuminate\Support\ServiceProvider;

class TalkServiceProvider extends ServiceProvider
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
        $this->app->singleton(TalkService::class, function ($app) {
            return new TalkService(config('lemurbot.tag'), new AimlMatcher(), new AIMLPArser());
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
