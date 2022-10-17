<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAppService;

class LemurInstallAppCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the default data required for the app to run';


    /**
     * Execute the console command.
     *
     * @param LemurBotInstallAppService $service
     * @return bool
     */
    public function handle(LemurBotInstallAppService $service)
    {
        $service->run();

    }

}
