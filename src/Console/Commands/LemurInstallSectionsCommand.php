<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Services\Install\LemurBotUpgradeService;

class LemurInstallSectionsCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-sections {--admin= : The email of the admin user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install sections';


    /**
     * Execute the console command.
     *
     * @param LemurBotUpgradeService $service
     * @return bool
     */
    public function handle(LemurBotUpgradeService $service)
    {
        if(empty($this->option('admin'))){
            $this->error('Missing the --admin email address parameter');
            $this->info('example: php artisan lemur:install-sections --admin=admin@lemurengine.local');
            return false;
        }else{
            $options['email'] = $this->option('admin');
        }
        $service->setOptions($options);
        $service->isolatedRun();

    }

}
