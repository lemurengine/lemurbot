<?php
namespace LemurEngine\LemurBot\Services\Install;

use Illuminate\Support\Facades\DB;
use Web64\Colors\Facades\Colors;
use Throwable;

class LemurBotInstallAllService
{

    public function run(?string $email, ?string $bot, ?string $aiml){


        $this->displayMessage("Installing Lemur Engine All Data", "title");

        //start the transaction
        DB::beginTransaction();

        try{
            $installApp = new LemurBotInstallAppService([]);
            $installApp->run();

            $installAdmin = new LemurBotInstallAdminService(['email'=>$email]);
            $installAdmin->run();

            $installSection = new LemurBotInstallSectionsService(['email'=>$email]);
            $installSection->run();

            $installWordLists = new LemurBotInstallWordListsService(['email'=>$email]);
            $installWordLists->run();

            $installBot = new LemurBotInstallBotService(['email'=>$email, 'bot'=>$bot]);
            $installBot->run();

            $installAiml = new LemurBotInstallAimlService(['email'=>$email, 'data'=>$aiml, 'bot'=>$bot]);
            $installAiml->run();

            DB::commit();

            $this->displayMessage("Install complete - dont forget to change your password", "title");


        }catch(Throwable $exception){
            DB::rollback();
            $this->displayMessage("An error occurred: ".$exception->getMessage()." ".basename($exception->getFile())."[".$exception->getLine()."]", 'error');
            $this->displayMessage('No changes made');
            return 0;
        }
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
