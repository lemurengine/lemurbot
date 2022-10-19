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
    protected $description = 'Create an admin user, a bot and some AIML knowledge for the bot.';


    /**
     * Execute the console command.
     *
     * @param LemurBotInstallAllService $service
     * @return bool
     */
    public function handle(LemurBotInstallAllService $service)
    {
        if(empty($this->option('admin'))){
            $this->error('Missing the --admin email address parameter');
            $this->info('example: php artisan lemur:install-all --admin=admin@lemurengine.local --bot=myBot --data=max');
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

        if(empty($this->option('bot'))){
            $this->error('Missing the --bot bot name parameter');
            $this->info('example: php artisan lemur:install-all --admin=admin@lemurengine.local --bot=myBot --data=max');
            return false;
        }else{
            $options['bot'] = $this->option('bot');
        }

        $bot = Bot::where('name',$options['bot'])->first();
        if($bot !== null){
            if (!$this->confirm('The bot '.$options['bot'].' already exists. Do you wish to continue?', true)) {
                $this->info('Exiting early - no changes made');
                return false;
            }
        }


        if(empty($this->option('data'))){
            $this->error('Missing the --data aiml parameter');
            $this->info('example: php artisan lemur:install-all --admin=admin@lemurengine.local --bot=myBot --data=max');
            return false;
        }else{
            $options['data'] = $this->option('data');
        }
        if($options['data'] !== null && !in_array($options['data'], ['min','max','none'])){
            $this->error('Please choose \'min\', \'max\', \'none\' for your AIML param');
            $this->info('example: php artisan lemur:install-all --admin=admin@lemurengine.local --bot=myBot --data=max');
            return false;
        }
        $service->setOptions($options);
        $service->run();

    }
}
