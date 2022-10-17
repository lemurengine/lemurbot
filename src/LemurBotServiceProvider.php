<?php
namespace LemurEngine\LemurBot;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\AboutCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallAdminCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallAimlCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallAppCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallBotCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallAllCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallSectionsCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallWordListsCommand;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Providers\AimlUploadServiceProvider;
use LemurEngine\LemurBot\Providers\BotPropertyUploadServiceProvider;
use LemurEngine\LemurBot\Providers\BotStatsServiceProvider;
use LemurEngine\LemurBot\Providers\MapValueUploadServiceProvider;
use LemurEngine\LemurBot\Providers\SetValueUploadServiceProvider;
use LemurEngine\LemurBot\Providers\TalkServiceProvider;
use LemurEngine\LemurBot\Providers\WordSpellingUploadServiceProvider;
use LemurEngine\LemurBot\Providers\WordTransformationUploadServiceProvider;
use LemurEngine\LemurBot\Services\LemurPrivilegeService;

class LemurBotServiceProvider extends ServiceProvider
{


    public function register()
    {
        $this->app->bind('lemur-privileges', LemurPrivilegeService::class);
        $loader = AliasLoader::getInstance();
        $loader->alias('LemurPriv', LemurPriv::class);

        $this->app->register(AimlUploadServiceProvider::class);
        $this->app->register(BotPropertyUploadServiceProvider::class);
        $this->app->register(BotStatsServiceProvider::class);
        $this->app->register(MapValueUploadServiceProvider::class);
        $this->app->register(SetValueUploadServiceProvider::class);
        $this->app->register(TalkServiceProvider::class);
        $this->app->register(WordSpellingUploadServiceProvider::class);
        $this->app->register(WordTransformationUploadServiceProvider::class);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/dropdown.php', 'lemurbot.dropdown'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/tag.php', 'lemurbot.tag'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/section.php', 'lemurbot.section'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/../config/version.php', 'lemurbot.version'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lemurbot');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'lemurbot');
        $this->publishes(
            [__DIR__ . '/../database/migrations/' => database_path('migrations/lemurbot')],
            'lemurbot-migrations');
        $this->publishes(
            [__DIR__ . '/../config/portal.php' => config_path('lemurbot/portal.php'),
                __DIR__ . '/../config/properties.php' => config_path('lemurbot/properties.php')],
            'lemurbot-config');
        $this->publishes([
            __DIR__.'/../resources/public'=> public_path('vendor/lemurbot')],
            'lemurbot-assets');
        $this->publishes([
            __DIR__.'/../resources/template/views/layouts/'=> resource_path('/views/layouts/'),
            __DIR__.'/../resources/template/views/home.blade.php'=> resource_path('/views/home.blade.php'),
            __DIR__.'/../resources/template/views/welcome.blade.php'=> resource_path('/views/welcome.blade.php')],
            'lemurbot-template');
        $this->publishes(
            [__DIR__ . '/../resources/widgets' => public_path('/widgets')],
            'lemurbot-widgets');
        $this->publishes(
            [__DIR__ . '/../resources/widgets' => public_path('/widgets')],
            'lemurbot-widgets');
        $this->publishes(
            [__DIR__ . '/../auth/views/auth' => resource_path('/views/auth'),
                __DIR__ . '/../auth/Controllers/Auth' => app_path('/Http/Controllers/Auth')],
            'lemurbot-auth');



        AboutCommand::add('Lemur Engine Package', fn () => [
            'Version' => config('lemurbot.version.release.version'),
            'Release Name' =>  config('lemurbot.version.release.name'),
            'Release Desc' => config('lemurbot.version.release.description'),
            'Release Info' => config('lemurbot.version.release.url')
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                LemurInstallAllCommand::class,
                LemurInstallAppCommand::class,
                LemurInstallAimlCommand::class,
                LemurInstallAdminCommand::class,
                LemurInstallBotCommand::class,
                LemurInstallSectionsCommand::class,
                LemurInstallWordListsCommand::class,
            ]);
        }

    }
}
