<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\User;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAllService;

class LemurInstallAllCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-all {--admin= : The email of the admin user} {--bot= : The name of the chatbot} {--data= : the data you want to add [none|min|max]}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install an admin user';


    /**
     * Execute the console command.
     *
     * @param LemurBotInstallAllService $service
     * @return bool
     */
    public function handle(LemurBotInstallAllService $service)
    {
        $admin = $this->option('admin');
        //lets do a little check if the admin user already exists...
        $user = User::where('email',$admin)->first();
        if($user !== null){
            if (!$this->confirm('The user '.$admin.' already exists. Do you wish to continue?', true)) {
                $this->info('Exiting early - no changes made');
                return false;
            }
        }
        $botName = $this->option('bot');
        $bot = Bot::where('name',$botName)->first();
        if($bot !== null){
            if (!$this->confirm('The bot '.$botName.' already exists. Do you wish to continue?', true)) {
                $this->info('Exiting early - no changes made');
                return false;
            }
        }
        $data = $this->option('data');
        if($data !== null && !in_array($data, ['min','max','none'])){
            $this->error('Please choose \'min\', \'max\', \'none\' for your AIML param');
            return false;
        }
        $service->run($admin, $botName, $data);

    }
}
