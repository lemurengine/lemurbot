<?php


namespace LemurEngine\LemurBot\Services\Install;

use Illuminate\Support\Facades\Auth;
use LemurEngine\LemurBot\Exceptions\InstallPrerequisitesException;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\User;
use Web64\Colors\Facades\Colors;

abstract class LemurBotInstallService
{

    protected $optionEmail;
    protected $optionBot;
    protected $optionData;
    protected $langId;
    protected $botId;


    public function __construct(?array $options)
    {
        $this->optionEmail=$options['email']??null;
        $this->optionBot=$options['bot']??null;
        $this->optionData=$options['data']??null;
    }


    /**
     * run the install (as part of a group)
     *
     * @return bool
     */
    abstract public function run();


    /**
     * run the install in isolation
     *
     * @return bool
     */
    abstract public function isolatedRun();




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

    protected function setAuthUser(){
        $user = User::where('email', $this->optionEmail)->first();
        if($user === null){
            throw New InstallPrerequisitesException("Unable to find admin user: '".$this->optionEmail."'");
        }else{
            Auth::login($user);
        }
    }

    protected function setLangId(){
        $lang = Language::where('name', 'en')->first();
        if($lang === null){
            throw New InstallPrerequisitesException("Unable to find language: 'English'");
        }else{
            $this->langId = $lang->id;
        }
    }

    protected function setBotId(){
        $bot = Bot::where('name', $this->optionBot)->where('user_id', Auth::id())->first();
        if($bot === null){
            throw New InstallPrerequisitesException("Unable to find bot: '".$this->optionBot."'");
        }else{
            $this->botId = $bot->id;
        }
    }

}
