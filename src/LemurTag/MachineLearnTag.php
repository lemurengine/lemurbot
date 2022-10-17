<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Models\EmptyResponse;
use LemurEngine\LemurBot\Models\Conversation;
use LemurEngine\LemurBot\Models\MachineLearntCategory;
use LemurEngine\LemurBot\Models\Wildcard;
use Illuminate\Support\Str;

/**
 * Class MachineLearnTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class MachineLearnTag extends AimlTag
{
    protected string $tagName = "MachineLearn";

    private string $learnTopic = "";
    private string $learnPattern = "";
    private string $learnThat = "";
    private string $learnExampleInput = "";
    private string $learnExampleOutput = "";
    private string $learnCleanCategoryGroupSlug = "";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * MachineLearn Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     * Saves the contents of this tag to the machine_learnt_categories table
     * replace occurences of [] to <> so that we can save tags in the template
     * e.g. if the contents is this [srai]foo[/srai] it will be replaced with this <srai<foo</srai>
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
        $this->learnTopic = $attributes['TOPIC']??'';
        $this->learnPattern = $attributes['PATTERN']??'';
        $this->learnThat = $attributes['THAT']??'';
        $this->learnExampleInput = $attributes['EXAMPLE_INPUT']??'';
        $this->learnExampleOutput = $attributes['EXAMPLE_OUTPUT']??'';
        $this->learnCleanCategoryGroupSlug = $attributes['CATEGORY_GROUP_SLUG']??'';

        $this->learnPattern = $this->cleanAndReplace($this->learnPattern);
    }

    public function cleanAndReplace($str){

        $str = $this->replaceSquareBrackets($str);
        preg_match_all('@<replace_star index="(.*?)"(.*?)/>|<replace_star />|<replace_star/>@is', $str, $matches);
        if(!empty($matches)){
            $str = preg_replace('@<replace_star index="(.*?)"(.*?)/>|<replace_star />|<replace_star/>@is', $this->getStarReplacement("$1"), $str);
            LemurLog::debug('after replacement', $str);
        }
        return $str;
    }

    public function getStarReplacement($offset){
        $star = Wildcard::where('conversation_id', $this->conversation->id)
            ->where('type', 'star')->latest('id')->skip($offset)->first();

        if ($star===null) {
            $value = '';
        } else {
            $value = $star->value;
        }
        return $value;
    }

    public function closeTag()
    {

        LemurLog::debug(
            __FUNCTION__,
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId(),
                'attributes'=>$this->getAttributes()
            ]
        );

        $template = $this->getCurrentTagContents(true);
        $template = $this->cleanAndReplace($template);

        $this->createOrUpdate($template);



    }

    public function createOrUpdate($template){

        $cleanPattern = LemurStr::normalizeForCategoryTable($this->learnPattern, ['set', 'bot']);
        $cleanTopic = LemurStr::normalizeForCategoryTable($this->learnTopic);
        $cleanThat = LemurStr::normalizeForCategoryTable($this->learnThat);
        $cleanCategorySlug = Str::slug(strtolower($this->learnCleanCategoryGroupSlug));

        $botId = $this->conversation->bot->id;

        $mlCategory = MachineLearntCategory::where('pattern', $cleanPattern)
            ->where('template', $template)
            ->where('that', $cleanThat)
            ->where('topic', $cleanTopic)
            ->where('category_group_slug', $cleanCategorySlug)
            ->where('bot_id', $botId)->first();

        if($mlCategory===null){
            $mlCategory = new MachineLearntCategory();
            $mlCategory->client_id = $this->conversation->client->id;
            $mlCategory->bot_id = $this->conversation->bot->id;
            $mlCategory->turn_id = $this->conversation->currentTurnId();
            $mlCategory->pattern = LemurStr::normalizeForCategoryTable($this->learnPattern, ['set', 'bot']);
            $mlCategory->template = $template;
            $mlCategory->topic = LemurStr::normalizeForCategoryTable($this->learnTopic);
            $mlCategory->that = LemurStr::normalizeForCategoryTable($this->learnThat);
            $mlCategory->example_input = $this->learnExampleInput;
            $mlCategory->example_output = $this->learnExampleOutput;
            $mlCategory->category_group_slug = $cleanCategorySlug;

            $mlCategory->save();
        }else{
            $mlCategory->occurrences=($mlCategory->occurrences+1);
            $mlCategory->save();
        }

    }

    public function replaceSquareBrackets($string){

        $string = str_replace("[","<",$string);
        return str_replace("]",">",$string);

    }
}
