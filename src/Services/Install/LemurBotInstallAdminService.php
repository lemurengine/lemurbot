<?php
namespace LemurEngine\LemurBot\Services\Install;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LemurEngine\LemurBot\Exceptions\InstallPrerequisitesException;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\User;
use Carbon\Carbon;
use Throwable;

class LemurBotInstallAdminService extends LemurBotInstallService
{


    public function isolatedRun()
    {
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

    /**
     * @throws InstallPrerequisitesException
     */
    public function run(){

        $this->displayMessage("Installing Lemur Engine Admin User", "title");
        $this->createUser();

    }

    /**
     * creates an admin user with default settings
     * these should be updated asap in the portal
     */
    public function createUser(){

        $user = User::firstOrCreate(
            ['email'=> $this->optionEmail],
            ['name' => 'Test User',
            'email_verified_at' => Carbon::now()]
        );

        if($user->wasRecentlyCreated){
            //change the password to password
            $user->password = Hash::make('password');
            $user->save();

            $this->displayMessage($this->optionEmail. " admin user created", "success");
            $this->displayMessage("Your password is 'password' please change it immediately", "notice");
        }else{
            $this->displayMessage($this->optionEmail. " admin user already exists", "notice");
        }

        //make sure this user is an admin user.
        $this->assignRole($user);

    }

    public function assignRole($user){

        $role = LemurPriv::assignRole($user->id, 'admin');
        if($role->wasRecentlyCreated){
            $this->displayMessage("Admin role created for user", "success");
        }else{
            $role->displayMessage("Admin role already exists for user", "notice");
        }


    }
}
