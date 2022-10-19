<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Models\User;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAdminService;

class LemurInstallAdminCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-admin {--admin= : The email of the admin user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user, or if the user already exist give them bot_admin privileges';


    /**
     * Execute the console command.
     *
     * @param LemurBotInstallAdminService $service
     * @return bool
     */
    public function handle(LemurBotInstallAdminService $service)
    {
        if(empty($this->option('admin'))){
            $this->error('Missing the --admin email address parameter');
            $this->info('example: php artisan lemur:install-admin --admin=admin@lemurengine.local');
            return false;
        }else{
            $options['email'] = $this->option('admin');
        }

        //lets do a little check if the admin user already exists...
        $user = User::where('email',$options['email'])->first();
        if($user !== null){
            if (!$this->confirm('The user '.$options['email'].' already exists. Do you wish to continue?', true)) {
                $this->info('Exiting early - no changes made');
                return false;
            }
        }

        $service->setOptions($options);
        $service->isolatedRun();

    }

}
