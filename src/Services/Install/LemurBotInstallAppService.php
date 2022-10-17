<?php
namespace LemurEngine\LemurBot\Services\Install;

use Illuminate\Support\Facades\DB;
use LemurEngine\LemurBot\Models\Language;
use Throwable;

class LemurBotInstallAppService extends LemurBotInstallService
{
    protected $userId;

    public function isolatedRun(){

        DB::beginTransaction();
        try{
            $this->run();
            DB::commit();
        }catch(Throwable $exception){
            DB::rollback();
            $this->displayMessage("An error occurred: ".$exception->getMessage()." ".basename($exception->getFile())."[".$exception->getLine()."]", "error");
            return 0;
        }
    }

    public function run(){
        $this->displayMessage("Installing Lemur Engine App Data", "title");
        //create the default language for the app
        $this->createDefaultLanguage();
    }

    /**
     * create the record for the english language
     */
    public function createDefaultLanguage(){

        $lang = Language::firstOrCreate(['slug' => 'en',
            'name' => 'en',
            'description' =>  'English']);
        if($lang->wasRecentlyCreated){
            $this->displayMessage("English language created", "success");
        }else{
            $this->displayMessage("English language already exists", "notice");
        }

    }

}
