<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\User;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallBotService;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class LemurInstallBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-bot {--admin= : The email of the admin user} {--bot= : The name of the chatbot}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the default data required for the app to run';


    /**
     * Execute the console command.
     *
     * @param LemurBotInstallBotService $service
     * @return bool
     */
    public function handle(LemurBotInstallBotService $service)
    {

        if(empty($this->option('admin'))){
            $this->error('Missing the --admin email address parameter');
            $this->info('example: php artisan lemur:install-bot --admin=admin@lemurengine.local --bot=myBot');
            return false;
        }else{
            $options['email'] = $this->option('admin');
        }

        if(empty($this->option('bot'))){
            $this->error('Missing the --bot bot name parameter');
            $this->info('example: php artisan lemur:install-bot --admin=admin@lemurengine.local --bot=myBot');
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
        $service->setOptions($options);
        $service->run();

    }

}
