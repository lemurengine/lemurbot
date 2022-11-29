<?php
namespace LemurEngine\LemurBot;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\AboutCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallAdminCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallAppCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallBotCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallAllCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallSectionsCommand;
use LemurEngine\LemurBot\Console\Commands\LemurInstallWordListsCommand;
use LemurEngine\LemurBot\Console\Commands\LemurUpgradeCommand;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Providers\AimlUploadServiceProvider;
use LemurEngine\LemurBot\Providers\BotPropertyUploadServiceProvider;
use LemurEngine\LemurBot\Providers\BotStatsServiceProvider;
use LemurEngine\LemurBot\Providers\Install\LemurBotInstallAdminServiceProvider;
use LemurEngine\LemurBot\Providers\Install\LemurBotInstallAimlServiceProvider;
use LemurEngine\LemurBot\Providers\Install\LemurBotInstallAllServiceProvider;
use LemurEngine\LemurBot\Providers\Install\LemurBotInstallAppServiceProvider;
use LemurEngine\LemurBot\Providers\Install\LemurBotInstallBotServiceProvider;
use LemurEngine\LemurBot\Providers\Install\LemurBotInstallSectionsServiceProvider;
use LemurEngine\LemurBot\Providers\Install\LemurBotInstallWordListsServiceProvider;
use LemurEngine\LemurBot\Providers\MapValueUploadServiceProvider;
use LemurEngine\LemurBot\Providers\SetValueUploadServiceProvider;
use LemurEngine\LemurBot\Providers\TalkServiceProvider;
use LemurEngine\LemurBot\Providers\WordSpellingUploadServiceProvider;
use LemurEngine\LemurBot\Providers\WordTransformationUploadServiceProvider;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAdminService;
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

        if ( app()->runningInConsole() ){
            $this->app->register(LemurBotInstallAdminServiceProvider::class);
            $this->app->register(LemurBotInstallAimlServiceProvider::class);
            $this->app->register(LemurBotInstallAllServiceProvider::class);
            $this->app->register(LemurBotInstallAppServiceProvider::class);
            $this->app->register(LemurBotInstallBotServiceProvider::class);
            $this->app->register(LemurBotInstallSectionsServiceProvider::class);
            $this->app->register(LemurBotInstallWordListsServiceProvider::class);
            // it's console.
        }

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

        $this->mergeConfigFrom(
            __DIR__ . '/../config/links.php', 'filesystems.links'
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
            __DIR__.'/../resources/public/lemurbot'=> public_path('vendor/lemurbot')
            ],
            'lemurbot-assets');
        $this->publishes([
            __DIR__ . '/../exampletag' => app_path('/LemurTag/'),
            __DIR__ . '/../exampleplugin' => app_path('/LemurPlugin/')
        ],
            'lemurbot-examples');
        $this->publishes([
            __DIR__ . '/../resources/public/widgets'=> public_path('widgets'),
            __DIR__ . '/../resources/widgets/avatar' => resource_path('vendor/lemurbot/widgets/avatar'),
            __DIR__ . '/../resources/widgets/popup' => resource_path('vendor/lemurbot/widgets/popup'),
            __DIR__ . '/../resources/widgets/images'=> resource_path('widgets/images'),
            __DIR__ . '/../resources/widgets/custom' => resource_path('widgets/custom'),
        ],
            'lemurbot-widgets');
        $this->publishes([
            __DIR__.'/../resources/template/public/'=>  public_path(),
            __DIR__.'/../resources/template/views/layouts/'=> resource_path('/views/layouts/'),
            __DIR__.'/../resources/template/views/home.blade.php'=> resource_path('/views/home.blade.php'),
            __DIR__.'/../resources/template/views/welcome.blade.php'=> resource_path('/views/welcome.blade.php')],
            'lemurbot-template');
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
                LemurUpgradeCommand::class,
                LemurInstallAllCommand::class,
                LemurInstallAppCommand::class,
                LemurInstallAdminCommand::class,
                LemurInstallBotCommand::class,
                LemurInstallSectionsCommand::class,
                LemurInstallWordListsCommand::class,
            ]);
        }

    }
}
