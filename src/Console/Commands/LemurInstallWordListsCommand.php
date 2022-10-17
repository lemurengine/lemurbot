<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Services\Installation\LemurBotInstallWordListsService;

class LemurInstallWordListsCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-word-lists {admin_email_address}';

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
        $emailAddress = $this->argument('admin_email_address');
        $service->run($emailAddress);

    }

}
