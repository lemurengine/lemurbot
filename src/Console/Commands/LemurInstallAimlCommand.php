<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAimlService;

class LemurInstallAimlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-aiml';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the default bot knowledge';


    /**
     * Execute the console command.
     *
     * @param LemurBotInstallAimlService $service
     * @return bool
     */
    public function handle(LemurBotInstallAimlService $service)
    {
        $botName = $this->argument('botname');
        $emailAddress = $this->argument('email');
        $userId = $service->createUser($emailAddress);
        if($botName){
            $botId = $service->createBot($emailAddress);
        }

    }

}
