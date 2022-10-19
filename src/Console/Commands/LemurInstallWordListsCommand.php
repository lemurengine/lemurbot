<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallWordListsService;

class LemurInstallWordListsCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-wordlists {--admin= : The email of the admin user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install sections';


    /**
     * Execute the console command.
     *
     * @param LemurBotInstallWordListsService $service
     * @return bool
     */
    public function handle(LemurBotInstallWordListsService $service)
    {

        if(empty($this->option('admin'))){
            $this->error('Missing the --admin email address parameter');
            $this->info('example: php artisan lemur:install-wordlists --admin=admin@lemurengine.local');
            return false;
        }else{
            $options['email'] = $this->option('admin');
        }

        $service->setOptions($options);
        $service->isolatedRun();

    }

}
