<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallBotService;

class LemurInstallBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-bot {bot_name} {bot_owner_email_address?}';

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
        $botName = $this->argument('bot_name');
        $emailAddress = $this->argument('bot_owner_email_address');
        $service->run($botName, $emailAddress);


    }

}
