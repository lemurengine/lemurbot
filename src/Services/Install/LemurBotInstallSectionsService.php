<?php
namespace LemurEngine\LemurBot\Services\Install;

use Illuminate\Support\Facades\DB;
use LemurEngine\LemurBot\Exceptions\InstallPrerequisitesException;
use LemurEngine\LemurBot\Models\Section;
use Throwable;

class LemurBotInstallSectionsService extends LemurBotInstallService
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
        $this->displayMessage("Installing Lemur Engine Section Data", "title");
        //auth the admin user
        $this->setupIds();
        //create the sections for the app
        $this->createSections();
    }


    /**
     * @throws InstallPrerequisitesException
     */
    public function setupIds(){

        $this->setAuthUser();


    }



    public function createSections()
    {
        $botPropertySections = config('lemurbot.section.bot_properties.sections');
        foreach($botPropertySections as $botPropertySectionSlug => $botPropertySectionSettings ){
            $section = Section::firstOrCreate(['slug' => $botPropertySectionSlug, 'name' => $botPropertySectionSettings['name']],
                ['order' => $botPropertySectionSettings['order'],
                    'type' => 'BOT_PROPERTY',
                    'default_state'=>$botPropertySectionSettings['default_state'],
                    'is_protected' => true]);
            if($section->wasRecentlyCreated){
                $this->displayMessage($botPropertySectionSettings['name']. " Section created", "success");
            }else{
                $this->displayMessage($botPropertySectionSettings['name']. " Section already exists", "notice");
            }
        }

        #-------------------------------------------

        $categoryGroupSections = config('lemurbot.section.category_groups.sections');

        foreach($categoryGroupSections as $categoryGroupSectionSlug => $categoryGroupSectionSettings ){
            $section = Section::firstOrCreate(
                ['slug' => $categoryGroupSectionSlug, 'name' => $categoryGroupSectionSettings['name']],
                [   'order' => $categoryGroupSectionSettings['order'],
                    'type' => 'CATEGORY_GROUP',
                    'default_state'=>$categoryGroupSectionSettings['default_state'],
                    'is_protected' => true]
            );

            if($section->wasRecentlyCreated){
                $this->displayMessage($categoryGroupSectionSettings['name']. " Section created", "success");
            }else{
                $this->displayMessage($categoryGroupSectionSettings['name']. " Section already exists", "notice");
            }

        }



    }


}
