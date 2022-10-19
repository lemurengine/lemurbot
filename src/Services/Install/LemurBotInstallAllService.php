<?php
namespace LemurEngine\LemurBot\Services\Install;

use Illuminate\Support\Facades\DB;
use Web64\Colors\Facades\Colors;
use Throwable;

class LemurBotInstallAllService
{

    private LemurBotInstallAppService $installAppService;
    private LemurBotInstallAdminService $installAdminService;
    private LemurBotInstallSectionsService $installSectionsService;
    private LemurBotInstallWordListsService $installWordListsService;
    private LemurBotInstallBotService $installBotService;
    private LemurBotInstallAimlService $installAimlService;

    private ?array $options;

    public function __construct(LemurBotInstallAppService $installAppService,
                                LemurBotInstallAdminService $installAdminService,
                                LemurBotInstallSectionsService $installSectionsService,
                                LemurBotInstallWordListsService $installWordListsService,
                                LemurBotInstallBotService $installBotService,
                                LemurBotInstallAimlService $installAimlService){


        $this->installAppService = $installAppService;
        $this->installAdminService = $installAdminService;
        $this->installSectionsService = $installSectionsService;
        $this->installWordListsService = $installWordListsService;
        $this->installBotService = $installBotService;
        $this->installAimlService = $installAimlService;

    }


    public function run(){


        $this->displayMessage("Installing Lemur Engine All Data", "title");

        //start the transaction
        DB::beginTransaction();

        try{
            $this->installAppService->setOptions($this->getOptions());
            $this->installAppService->run();

            $this->installAdminService->setOptions($this->getOptions());
            $this->installAdminService->run();

            $this->installSectionsService->setOptions($this->getOptions());
            $this->installSectionsService->run();

            $this->installWordListsService->setOptions($this->getOptions());
            $this->installWordListsService->run();

            $this->installBotService->setOptions($this->getOptions());
            $this->installBotService->run();

            $this->installAimlService->setOptions($this->getOptions());
            $this->installAimlService->run();

            DB::commit();

            $this->displayMessage("Install complete - dont forget to change your password", "title");


        }catch(Throwable $exception){
            DB::rollback();
            $this->displayMessage("An error occurred: ".$exception->getMessage()." ".basename($exception->getFile())."[".$exception->getLine()."]", 'error');
            $this->displayMessage('No changes made');
            return 0;
        }
    }


    public function setOptions(?array $options){
        $this->options=$options;
    }


    public function getOptions(){
        return $this->options;
    }

    /**
     * display a message to the console with colors
     *
     * @return bool
     */
    public function displayMessage($message, $type='info'):void{

        switch ($type) {
            case 'error':
                Colors::red($message);
                break;
            case 'success':
                Colors::green($message);
                break;
            case 'notice':
                Colors::yellow($message);
                break;
            case 'title':
                Colors::white("\n--------------------------------------\n$message\n--------------------------------------");
                break;
            default:
                Colors::white($message);
                break;
        }
    }

}
