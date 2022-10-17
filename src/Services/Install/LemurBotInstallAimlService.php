<?php
namespace LemurEngine\LemurBot\Services\Install;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LemurEngine\LemurBot\Exceptions\InstallMissingDataDependencyException;
use LemurEngine\LemurBot\Exceptions\InstallPrerequisitesException;
use LemurEngine\LemurBot\Models\BotCategoryGroup;
use LemurEngine\LemurBot\Models\CategoryGroup;
use LemurEngine\LemurBot\Models\Section;
use LemurEngine\LemurBot\Services\AimlUploadService;

class LemurBotInstallAimlService extends LemurBotInstallService
{

    const MIN_DATASET = ['dev-testcases', 'std-critical', 'std-rating', 'std-hello'];

    protected AimlUploadService $aimlUploadService;

    public function run()
    {
        $this->displayMessage("Installing Lemur Engine AIML", "title");
        if($this->optionData == 'none'){
            $this->displayMessage("No datasets selected", "notice");
        }else{
            $this->setupIds();
            $this->aimlUploadService = new AimlUploadService();
            $dataCategorySlugs = $this->getCategoryList();
            $this->createCategoriesLinkBotsAddData($dataCategorySlugs);
        }
    }

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
    public function setupIds(){

        $this->setAuthUser();

        $this->setLangId();

        $this->setBotId();

    }

    public function getCategoryList(){

        if($this->optionData == 'min'){
            $dataCategorySlugs = Self::MIN_DATASET;
        }elseif($this->optionData == 'max'){
            $dataCategorySlugs = array_keys($this->getKnowledgeCategories());
        }else{
            $dataCategorySlugs = null;
        }
        return $dataCategorySlugs;


    }

    public function createCategoriesLinkBotsAddData($dataCategorySlugs){

        $dataCategories = $this->getKnowledgeCategories();
        foreach($dataCategorySlugs as $dataCategorySlug){
            $dataCategory = $dataCategories[$dataCategorySlug];
            $categoryGroup = $this->createAndGetCategoryGroup($dataCategory);
            $this->linkCategoryGroupToBot($categoryGroup);
            if($categoryGroup->wasRecentlyCreated){
                $this->displayMessage($dataCategorySlug." category group created and linked to bot", "success");
            }else{
                $this->displayMessage($dataCategorySlug." category group already exist and linked to bot", "notice");
            }
            $categoryResults = $this->importKnowledge($dataCategorySlug, $categoryGroup);
            if($categoryResults['new']>0){
                $this->displayMessage($categoryResults['new']." $dataCategorySlug knowledge categories created", "success");
            }
            if($categoryResults['existing']>0){
                $this->displayMessage($categoryResults['existing']." $dataCategorySlug knowledge categories already exist", "notice");
            }

        }



    }


    public function createAndGetCategoryGroup($dataCategory)
    {
        return CategoryGroup::firstOrCreate(
            ['language_id' => $this->langId, 'name' => $dataCategory['name']],
            ['description' => $dataCategory['description'],
            'status' => $dataCategory['status'],
            'is_master' => $dataCategory['is_master'],
            'section_id' => $this->getSectionId($dataCategory['section_id'])
        ]);

    }


    public function linkCategoryGroupToBot($categoryGroup)
    {
        return BotCategoryGroup::firstOrCreate([
            'bot_id' => $this->botId, 'category_group_id' =>$categoryGroup->id,
        ]);
    }

    /**
     * @param $dataCategorySlug
     * @param $dataCategory
     * @param $categoryGroup
     *
     *array:6 [
        0 => "Filename"
        1 => "Pattern"
        2 => "Topic"
        3 => "That"
        4 => "Template"
        5 => "Status"
        ]
     */
    public function importKnowledge($dataCategorySlug, $categoryGroup){

        $newCount=0;
        $existCount=0;
        $filePath = $this->getFilePathFromSlug($dataCategorySlug);
        $file = fopen($filePath, "r");
        $header = true;
        while ( ($data = fgetcsv($file, 0, ",",'"','/')) !==FALSE ) {
            if($header){
                //skip first line its the header
                $header = false;
                continue;
            }
            $categoryResult = $this->aimlUploadService->createOrUpdateCategory($categoryGroup->id, $data[1], $data[2], $data[3], $data[4], $data[5]);
            if($categoryResult== 'new'){
                $newCount++;
            }else{
                $existCount++;
            }
        }

        return ['new'=>$newCount, 'existing'=>$existCount];




    }

    public function getFilePathFromSlug($dataCategorySlug){

        return __DIR__ . '/../../../aiml/'.$dataCategorySlug.'.csv';

    }



    public function getSectionId($sectionSlug){

        try {
            $section = Section::where('user_id', Auth::id())->where('slug', $sectionSlug)->firstOrFail('id');
            return $section->id;
        }catch (ModelNotFoundException $e){
            Throw new InstallMissingDataDependencyException("Cannot find section: \'$sectionSlug\'");
        }

    }

    public function getKnowledgeCategories(){
        return [
            'dev-testcases'=>[
                'name'=>'dev-testcases',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group is used in development to test everything is working as expected.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'test-categories'
            ],
            'std-critical'=>[
                'name'=>'std-critical',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains a few critical categories. Make sure this is always linked to your bot .',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'critical-flow'
            ],
            'std-rating'=>[
                'name'=>'std-rating',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains patterns to request clients to rate your bot.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'special-routines'
            ],
            'std-hello'=>[
                'name'=>'std-hello',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to common greetings and goodbyes.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'conversation-flow'
        ],
            'std-65percent'=>[
                'name'=>'std-65percent',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to 65% of the conversation inputs your bot will receive.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'conversation-flow'
            ],
            'std-atomic'=>[
                'name'=>'std-atomic',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to small one liners from the client.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'conversation-flow'
            ],
            'std-botmaster'=>[
                'name'=>'std-botmaster',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to questions about the bot creator.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'personality'
            ],
            'std-brain'=>[
                'name'=>'std-brain',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to common conversations inputs.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],
            'std-dictionary'=>[
                'name'=>'std-dictionary',
                'description'=>"Part of the master set of Lemur Engine AIML categories. This group contains responses to requests to 'define' a word.",
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
           ],
            'std-howto'=>[
                'name'=>'std-howto',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of the Lemur Engine and how to use, extend and talk to the bot.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],
            'std-inventions'=>[
                'name'=>'std-inventions',
                'description'=>"Part of the master set of Lemur Engine AIML categories. This group contains responses to questions about inventions.",
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],
            'std-gender'=>[
                'name'=>'std-gender',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains categories to determine a clients gender.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'conversation-flow'
           ],
            'std-geography'=>[
                'name'=>'std-geography',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of geographical locations.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
           ],
            'std-german'=>[
                'name'=>'std-german',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations spoken in german.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],
            'std-gossip'=>[
                'name'=>'std-gossip',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of gossip.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'special-routines'
            ],
            'std-knowledge'=>[
                'name'=>'std-knowledge',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to general questions on a range of subjects.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],
            'std-lizards'=>[
                'name'=>'std-lizards',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of lizards.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],
            'std-numbers'=>[
                'name'=>'std-numbers',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to basic math questions.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],
            'std-personality'=>[
                'name'=>'std-personality',
                'description'=>"Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of the client and the bot\'s personality.",
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'personality'
            ],
            'std-pickup'=>[
                'name'=>'std-pickup',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains catchall responses when the bot gets confused.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'conversation-flow'
            ],
            'std-politics'=>[
                'name'=>'std-politics',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of politics.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],'std-profile'=>[
                'name'=>'std-profile',
                'description'=>"Part of the master set of Lemur Engine AIML categories. This group contains conversations about the client's profile.",
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'personality'
            ],'std-religion'=>[
                'name'=>'std-religion',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of religion.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],'std-robot'=>[
                'name'=>'std-robot',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the subject of robots.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],'std-sports'=>[
                'name'=>'std-sports',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses around the subject sports.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],'std-sales'=>[
                'name'=>'std-sales',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses around the subject sales and customer service. The bot will respond as if it is a customer service bot.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'special-routines'
            ],'std-sextalk'=>[
                'name'=>'std-sextalk',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the topic of sex. Yes you read right. Whether we like it or not this is a common conversation topic.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],'std-srai'=>[
                'name'=>'std-srai',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group reduces common client inputs to single category and responds using that common category.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'conversation-flow'
            ],'std-that'=>[
                'name'=>'std-that',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group catches and responds to client based upon how the bot just replied. This gives the conversation greater content and flow.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'conversation-flow'
            ],'std-suffixes'=>[
                'name'=>'std-suffixes',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group catches and responds to general conversations by identifying common topics using a wildcard prefix and a subject suffix pattern match.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'conversation-flow'
            ],'std-turing'=>[
                'name'=>'std-turing',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group provides bot responses to questions and interactions about the English mathematician, computer scientist and all round hero Alan Turing.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],'std-yesno'=>[
                'name'=>'std-yesno',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group provides bot responses after the client has said yes or no.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'conversation-flow'
            ],'std-learn'=>[
                'name'=>'std-learn',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group provides categories to help the bot learn from the client.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'special-routines'
            ],'std-happybirthday'=>[
                'name'=>'std-happybirthday',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group provides conversations around birthdays.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],'std-horoscope'=>[
                'name'=>'std-horoscope',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group contains responses to conversations on the topic of horoscopes.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],'std-howmany'=>[
                'name'=>'std-howmany',
                'description'=>"Part of the master set of Lemur Engine AIML categories. This group contains responses to a clients 'how many?' questions.",
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'general-knowledge'
            ],'std-jokes'=>[
                'name'=>'std-jokes',
                'description'=>'Part of the master set of Lemur Engine AIML categories. This group provides jokes.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'fun-talk'
            ],'std-knockknock'=>[
                'name'=>'std-knockknock',
                'description'=>'Part of the master set of Lemur Engine AIML categories. TThis group provides knock knock jokes.',
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'fun-talk'
            ],'std-yomama'=>[
                'name'=>'std-yomama',
                'description'=>"Part of the master set of Lemur Engine AIML categories. TThis group provides 'yo mama' jokes.",
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'fun-talk'
            ],'std-shutup'=>[
                'name'=>'std-shutup',
                'description'=>"Part of the master set of Lemur Engine AIML categories. This group provides bot responses after the client told the bot to 'shut up'.",
                'status'=>'A',
                'is_master'=>1,
                'section_id'=>'conversation-flow'
            ]
        ];
    }
}
