<?php


namespace LemurEngine\LemurBot\Services\Install;

use Illuminate\Support\Facades\Auth;
use LemurEngine\LemurBot\Exceptions\InstallPrerequisitesException;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\User;
use LemurEngine\LemurBot\Traits\DisplayMessageTrait;
use Web64\Colors\Facades\Colors;

abstract class LemurBotInstallService
{
    use DisplayMessageTrait;

    protected $optionEmail;
    protected $optionBot;
    protected $optionData;
    protected $langId;
    protected $botId;
    protected $userId;


    public function setOptions(?array $options){
        $this->optionEmail=$options['email']??null;
        $this->optionBot=$options['bot']??null;
        $this->optionData=$options['data']??null;
    }


    public function getOptions(){

        return [
            'optionEmail'=>$this->optionEmail,
            'optionBot'=>$this->optionBot,
            'optionData'=>$this->optionData,
        ];
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


    protected function setAuthUser(){
        $user = User::where('email', $this->optionEmail)->first();
        if($user === null){
            throw New InstallPrerequisitesException("Unable to find admin user: '".$this->optionEmail."'");
        }else{
            Auth::login($user);
        }
        $this->userId = Auth::id();
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

    public function getUserId(){
        return $this->userId;
    }

}
