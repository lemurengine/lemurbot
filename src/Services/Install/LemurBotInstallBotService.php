<?php
namespace LemurEngine\LemurBot\Services\Install;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LemurEngine\LemurBot\Exceptions\InstallPrerequisitesException;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\BotProperty;
use LemurEngine\LemurBot\Models\Section;
use Throwable;

class LemurBotInstallBotService extends LemurBotInstallService
{

    protected $botId;
    protected $botPersonalitySectionId;
    protected $socialMediaSectionId;

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

        $this->displayMessage("Installing Lemur Engine Bot And It's Property Data", "title");

        $this->setupIds();
        $this->botId = $this->createBot();
        $this->createBotProperties();

    }


    /**
     * @throws InstallPrerequisitesException
     */
    public function setupIds(){

        $this->setAuthUser();

        $this->setLangId();

        $section = Section::where('user_id', Auth::id())->where('name','Bot Personality')->first();
        if($section === null){
            throw new InstallPrerequisitesException("Bot Personality section does not exist - have you run 'php artisan lemur:install-app' to add all the required data to the app?");
        }else{
            $this->botPersonalitySectionId = $section->id;
        }

        $section = Section::where('user_id', Auth::id())->where('name','Social Media')->first();
        if($section === null){
            throw new InstallPrerequisitesException("Social Media section does not exist - have you run 'php artisan lemur:install-app' to add all the required data to the app?");
        }else{
            $this->socialMediaSectionId = $section->id;
        }
    }


    public function createBot(){

        $bot = Bot::firstOrCreate(
            ['name'=> $this->optionBot, 'user_id'=> Auth::id()],
            ['language_id' => $this->langId,
                'name' => $this->optionBot,
                'summary' => "The default conversation bot",
                'description' => "This bot has been created with the lemur:install-bot command",
                'default_response' => 'I don\'t have a response for that',
                'lemurtar_url' => 'https://lemurtar.com/?accessoriesType=Blank&avatarStyle=Circle&clotheColor=Pink&clotheType=Overall&eyeType=Default&eyebrowType=DefaultNatural&facialHairColor=Brown&facialHairType=Blank&graphicType=Pizza&hairColor=Black&hatColor=Blue02&mouthType=Twinkle&skinColor=Tanned&topType=ShortHairShaggyMullet',
                'status' => 'A',
                'image' => 'widgets/images/robot.png',
                'is_public' => 1]);

        if($bot->wasRecentlyCreated){
            $this->displayMessage("bot ".$this->optionBot." created", "success");
        }else{
            $this->displayMessage("bot ".$this->optionBot." already exists", "notice");
        }

        //return the bot id
        return $bot->id;

    }
    public function createBotProperties()
    {
        $this->createBotPropertyFields($this->botPersonalitySectionId, $this->getDefaultConfigProperties('bot_personality', [['name',$this->optionBot]]),'bot_personality');
        $this->createBotPropertyFields($this->socialMediaSectionId, $this->getDefaultConfigProperties('social_media'), 'social_media');
    }

    public function createBotPropertyFields($sectionId, $properties, $which)
    {
        $newCount = 0;
        $existCount = 0;
        foreach($properties as $data){
            $name = $data[0];
            $value = $data[1];
            $botProperty = BotProperty::firstOrCreate(
                ['bot_id'=> $this->botId, 'name'=>$name, 'user_id'=> Auth::id()],
                ['value' => $value, 'section_id' => $sectionId]);
            if($botProperty->wasRecentlyCreated){
                $newCount++;
            }else{
                $existCount++;
            }
        }
        if($newCount>0){
            $this->displayMessage($newCount." $which bot properties created", "success");
        }
        if($existCount>0){
            $this->displayMessage($existCount." $which bot properties already exists", "notice");
        }
    }



    public function getDefaultConfigProperties($which, $overwrite = [])
    {
        $botPropertiesFromConfig = config('lemurbot.properties.'.$which);
        if(!empty($overwrite)){
            $botPropertiesFromConfig = array_merge($botPropertiesFromConfig,$overwrite);
        }
        return $botPropertiesFromConfig;

    }

}
