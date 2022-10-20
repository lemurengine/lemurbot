<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\User;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAllService;
use LemurEngine\LemurBot\Services\Upgrade\LemurBotUpgradeService;

class LemurUpgradeCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:upgrade {version : the version you are upgrading to}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Performs any required upgrade tasks';


    /**
     * Execute the console command.
     *
     * @param LemurBotUpgradeService $service
     * @return bool
     */
    public function handle(LemurBotUpgradeService $service)
    {
        if(empty($this->argument('version'))){
            $this->error('Missing the version you are upgrading to');
            $this->info('example: php artisan lemur:upgrade 9.0.0');
            return false;
        }else{
            $version = $this->argument('version');
        }

        $service->run($version);

    }
}
