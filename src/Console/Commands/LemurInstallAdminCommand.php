<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallAdminService;

class LemurInstallAdminCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install an admin user';


    /**
     * Execute the console command.
     *
     * @param LemurBotInstallAdminService $service
     * @return bool
     */
    public function handle(LemurBotInstallAdminService $service)
    {
        $emailAddress = $this->argument('email');
        $service->run($emailAddress);

    }

}
