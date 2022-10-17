<?php

namespace LemurEngine\LemurBot\Console\Commands;

use Illuminate\Console\Command;
use LemurEngine\LemurBot\Services\Install\LemurBotInstallSectionsService;

class LemurInstallSectionsCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lemur:install-sections {admin_email_address}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install sections';


    /**
     * Execute the console command.
     *
     * @param LemurBotInstallSectionsService $service
     * @return bool
     */
    public function handle(LemurBotInstallSectionsService $service)
    {
        $emailAddress = $this->argument('admin_email_address');
        $service->run($emailAddress);

    }

}
