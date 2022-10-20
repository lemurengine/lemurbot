<?php
namespace LemurEngine\LemurBot\Services\Upgrade;

use LemurEngine\LemurBot\Traits\DisplayMessageTrait;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * this upgrade service will fix some data that might exist in the old unpackaged version of lemur engine
 * so that it doesnt conflict with the packaged version
 */
class LemurBotUpgradeService
{
    use DisplayMessageTrait;

    public function isolatedRun(){

        DB::beginTransaction();
        try{
            $this->run();
            DB::commit();
            $this->displayMessage("Complete", "success");
        }catch(Throwable $exception){
            DB::rollback();
            $this->displayMessage("An error occurred: ".$exception->getMessage()." ".basename($exception->getFile())."[".$exception->getLine()."]", "error");
            return 0;
        }
    }


    public function run($version){

        $this->displayMessage("Upgrading to $version", "title");

        if($version === '9.0.0'){
            //auth the admin user
            $this->renameMigrationFilesBackToDefaultNames('2021_04_06_083728_create_users_table', '2014_10_12_000000_create_users_table');
            $this->renameMigrationFilesBackToDefaultNames('2021_04_06_083728_create_password_resets_table', '2014_10_12_100000_create_password_resets_table');
            $this->renameMigrationFilesBackToDefaultNames('2021_04_06_083728_create_failed_jobs_table', '2019_08_19_000000_create_failed_jobs_table');
            $this->renameMigrationFilesBackToDefaultNames('2021_04_06_083728_create_personal_access_tokens_table', '2019_12_14_000001_create_personal_access_tokens_table');
        }
    }

    public function renameMigrationFilesBackToDefaultNames($oldName, $newName){


        DB::table('migrations')
            ->where('migration', $oldName)
            ->update(['migration' => $newName]);

        $this->displayMessage("Updated $oldName to $newName", "info");


    }







}
