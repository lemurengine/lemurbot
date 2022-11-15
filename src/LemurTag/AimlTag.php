<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Classes\TagStack;
use Exception;
use LemurEngine\LemurBot\Models\Conversation;
use SimpleXMLElement;

/**
 * Class AimlTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
abstract class AimlTag implements AimlTagInterface
{

    protected array $attributes = [];
    protected string $tagName = '';
    protected string $tagId;
    protected Conversation $conversation;
    protected array $tagContents = [];
    protected array $tagSettings = [];
    protected bool $isTagValid=true;

    const TAG_STANDARD = 'standard';
    const TAG_RECURSIVE = 'recursive';
    const TAG_HTML = 'html';

    static $aimlTagType = self::TAG_STANDARD;
    /**
     * AimlTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        $this->setAttributes($attributes);
        $this->conversation = $conversation;
        $this->setTagId();
    }

    public static function getAimlTagType(){
        return static::$aimlTagType;
    }

    public function getMember($member_name)
    {

        return $this->$member_name;
    }

    /**
     * @param bool $forceName
     * 99.9% of the time we want to let the app make and set the tagId
     */
    public function setTagId($forceName = false)
    {
        if (!$forceName) {
            $this->tagId = uniqid($this->tagName.'_', false);
        } else {
            $this->tagId = $forceName;
        }
    }

    public function setIsTagValid($value)
    {

        $this->isTagValid = $value;

        LemurLog::warn('Setting tag validity', [
            'current'=>$this->getTagId(),
            'valid'=>(int)$this->isTagValid
        ]);
    }

    public function getIsTagValid()
    {
        return $this->isTagValid;
    }

    public function getTagId()
    {
        return $this->tagId;
    }

    /**
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }


    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        if(!empty($attributes)){
            foreach ($attributes as $index => $value) {
                $this->attributes[$index]=$value;
            }
        }

    }


    /**
     * @param string $attribute
     * @param $value
     */
    public function setAttribute(string $attribute, $value)
    {
        $this->attributes[$attribute]=$value;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function hasAttributes(): bool
    {

        if (!empty($this->attributes)) {
            return true;
        } else {
            return false;
        }
    }


    public function checkAttribute($attribute_name, $value)
    {
        if (!isset($this->attributes[$attribute_name])) {
            return false;
        } else {
            return ($this->attributes[$attribute_name] === $value);
        }
    }

    public function getAttribute($attribute_name, $default = '')
    {
        return ($this->attributes[$attribute_name]??$default);
    }


    public function hasAttribute($attribute_name)
    {
        return isset($this->attributes[$attribute_name]);
    }

    /**
     * nothing to do ...
     * this is intentionally left empty
     * @param $tagSettings
     */
    public function openTag($tagSettings)
    {

        LemurLog::info(
            __FUNCTION__,
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId(),
                'settings'=>$tagSettings,
                'attributes'=>$this->getAttributes()
            ]
        );

        $this->tagSettings=$tagSettings;
    }

    /**
     * when we close the tag
     * just return send the response all the way up the tag stack
     * all the way up to the template tag
     *
     * @return string
     */
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

        //as soon as we have processed the tag send if to the previous tag in the stack

        //if we are in learning mode we will do something else instead of evaluating the contents
        //it will turn it back into aiml for saving...
        if (!empty($this->tagContents)) {
            $contents = implode(" ", $this->tagContents);

            if ($this->isInLearningMode() || $this->isInLiTag()) {
                $contents = $this->buildAIMLIfInDoNotParseMode($contents);
            } elseif ($this->isInLiTag()) {
                $contents = $this->buildAIMLIfInDoNotParseMode($contents);
            }
        } else {
            $contents = '';
        }

        $this->tagContents = [];
        $this->tagContents[] = $contents;
    }

    /**
     * @param $contents
     */
    public function processContents($contents)
    {

        LemurLog::debug(
            __FUNCTION__,
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId(),
                'attributes'=>$this->getAttributes(),
                'contents'=>$contents
            ]
        );

        if (is_array($contents)) {
            $contents=implode(" ", $contents);
        }

        $this->buildResponse($contents);
    }



    /**
     * nothing to do ...
     * this is intentionally left empty
     */
    public function __destruct()
    {

        LemurLog::info(
            __FUNCTION__,
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId(),
            ]
        );
    }

    public function getTagContentsCompact()
    {
        return implode(" ", $this->tagContents);
    }

    /**
     * if we are in learning mode then the contents on a tag is not parsed
     * it is just returned so it can be added to the new AIML category...
     * UNLESS.....
     * are in in <eval> mode
     *
     * e.g. <learn><eval><date/></eval></learn> = <learn>2016-11-11</learn>
     * and
     * <learn><date/></learn> = <learn><date/></learn>
     *
     * @return mixed
     */
    public function isInLearningMode($msg = '')
    {


        if ($this->getTagName()=='Evaluate') {
            LemurLog::info(
                'Not in learning mode',
                [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId(),
                ]
            );

            return false;
        }

        $maxIndex = $this->getTagStack()->maxPosition();



        if ($maxIndex!==false) {
            for ($i=$maxIndex; $i>=0; $i--) {
                $inStackTagName = $this->getTagStack()->item($i)->getTagName();

                if ($inStackTagName=='Evaluate') {
                    LemurLog::info(
                        'Not in learning mode',
                        [
                            'conversation_id'=>$this->conversation->id,
                            'turn_id'=>$this->conversation->currentTurnId(),
                            'tag_id'=>$this->getTagId(),
                        ]
                    );
                    return false;
                } elseif ($inStackTagName=='Learn') {
                    LemurLog::info(
                        'In learning mode',
                        [
                            'conversation_id'=>$this->conversation->id,
                            'turn_id'=>$this->conversation->currentTurnId(),
                            'tag_id'=>$this->getTagId(),
                        ]
                    );
                    return true;
                }
            }
        }

        return false;
    }




    /**
     * if we are in learning mode then the contents on a tag is not parsed
     * it is just returned so it can be added to the new AIML category...
     * UNLESS.....
     * are in in <eval> mode
     *
     * e.g. <learn><eval><date/></eval></learn> = <learn>2016-11-11</learn>
     * and
     * <learn><date/></learn> = <learn><date/></learn>
     *
     * @return mixed
     */
    public function isInLiTag()
    {

        if ($this->getTagName()=='Li') {
            LemurLog::info(
                'Is in a li tag',
                [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId(),
                ]
            );
            return true;
        }


        $maxIndex = $this->getTagStack()->maxPosition();

        if ($maxIndex) {
            for ($i=$maxIndex; $i>=0; $i--) {
                $inStackTagName = $this->getTagStack()->item($i)->getTagName();

                if ($inStackTagName=='Li') {
                    LemurLog::info(
                        'Is in a li tag',
                        [
                            'conversation_id'=>$this->conversation->id,
                            'turn_id'=>$this->conversation->currentTurnId(),
                            'tag_id'=>$this->getTagId(),
                        ]
                    );
                    return true;
                }
            }
        }

        return false;
    }


    public function isInRandomTag()
    {

        if ($this->getTagName()=='Random') {
            LemurLog::info(
                'Is in a Random tag',
                [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId(),
                ]
            );
            return true;
        }


        $maxIndex = $this->getTagStack()->maxPosition();

        if ($maxIndex) {
            for ($i=$maxIndex; $i>=0; $i--) {
                $inStackTagName = $this->getTagStack()->item($i)->getTagName();


                if ($inStackTagName=='Random') {
                    LemurLog::info(
                        'Is in a Random tag',
                        [
                            'conversation_id'=>$this->conversation->id,
                            'turn_id'=>$this->conversation->currentTurnId(),
                            'tag_id'=>$this->getTagId(),
                        ]
                    );
                    return true;
                }
            }
        }

        return false;
    }



    public function buildAIMLIfInLearnMode($contents)
    {

        if ($this->isInLearningMode()) {
            return $this->buildAIMLIfInDoNotParseMode($contents);
        }
        return '';
    }

    public function buildAIMLIfInLiMode($contents)
    {

        $contents = trim($contents);

        if ($this->isInLiTag()) {
            return $this->buildAIMLIfInDoNotParseMode($contents);
        }
        return '';
    }


    public function buildAIMLIfInDoNotParseMode($contents)
    {

        $tagName = mb_strtolower($this->getTagName());

        if ($tagName!='') {
            $contentsWithTags = <<<XML
<{$tagName}>$contents</{$tagName}>
XML;
            //convert to xml
            $aiml = new SimpleXMLElement($contentsWithTags);
            //so we can add the tags in if needs be
            if ($this->hasAttributes()) {
                foreach ($this->attributes as $index => $value) {
                    $aiml->addAttribute($index, $value);
                }
            }

            $contents = trim(preg_replace('#<\?xml.*\?>#', '', $aiml->asXML()));
        }

        return $contents;
    }








    public function getTagSetting($key, $default = false)
    {

        return  (isset($this->tagSettings[$key])?$this->tagSettings[$key]:$default);
    }





    /**
     * move through the tag stack and look for the first random or the first condition
     *
     * @return mixed
     */
    public function getPreviousTagObject($find_tag_name = false)
    {


        if (!$find_tag_name) {
            LemurLog::info(
                'Getting previous item',
                [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId(),
                ]
            );
            return $this->getTagStack()->previousItem();
        }

        for ($i=$this->getTagStack()->maxPosition(); $i>=0; $i--) {
            $inStackTagName = $this->getTagStack()->item($i)->getTagName();

            if ($inStackTagName == $find_tag_name) {
                LemurLog::info(
                    'Getting previous item',
                    [
                        'conversation_id'=>$this->conversation->id,
                        'turn_id'=>$this->conversation->currentTurnId(),
                        'tag_id'=>$this->getTagId(),
                    ]
                );

                return $this->getTagStack()->item($i);
            }
        }


        LemurLog::warn(
            'Could not find previous item',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId()
            ]
        );

        return false;
    }

    /**
     * move through the tag stack and look for the first random or the first condition
     *
     * @param array $tagNameArr
     * @return mixed
     * @throws Exception
     */
    public function getPreviousTagByNames($tagNameArr = [])
    {

        LemurLog::info(
            'Getting previous tags by names',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId(),
                'tag_names' => $tagNameArr
            ]
        );


        //get second to last max position
        $max=$this->getTagStack()->maxPosition();

        if (empty($tagNameArr)) {
            return ['tag'=>$this->getTagStack()->previousItem(),'position'=>$max];
        }



        //if it exists... do the loop backwards
        if ($max) {
            for ($i=$max; $i>=0; $i--) {
                if ($this->getTagStack()->exists($i)) {
                    $tag = $this->getTagStack()->item($i);
                    $tagName = $tag->getTagName();

                    if (in_array($tagName, $tagNameArr)) {
                        LemurLog::info(
                            'Found tag',
                            [
                                'conversation_id'=>$this->conversation->id,
                                'turn_id'=>$this->conversation->currentTurnId(),
                                'tag_id'=>$this->getTagId(),
                            ]
                        );

                        return ['tag'=>$tag,'position'=>$i];
                    }
                } else {
                    LemurLog::warn(
                        'Tag does not exist',
                        [
                            'conversation_id'=>$this->conversation->id,
                            'turn_id'=>$this->conversation->currentTurnId(),
                            'tag'=>$i
                        ]
                    );

                    throw new Exception('Previous tag does not exist: '.$i);
                }
            }
        }


        //else false...
        return false;
    }


    /**
     * a helper method to return the parent tag from the stack
     *
     *
     * @return bool
     */
    public function getPreviousTagClassFromStack()
    {

        return $this->getTagStack()->previousItem();
    }


    /**
     * get any tag from the class by passing the position
     *
     * @param $position
     * @return bool
     */
    public function getTagClassFromStack($position)
    {

        if ($position=='current') {
            return $this->getTagStack()->lastItem();
        } elseif ($position=='previous') {
            return $this->getTagStack()->previousItem();
        }
    }


    public function getCurrentTagContents($clear = true)
    {

        $contents = LemurStr::cleanAndImplode($this->tagContents);
        if ($clear) {
            $this->tagContents=[];
        }

        LemurLog::info(
            'getCurrentTagContents',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId(),
                'contents' => $contents,
            ]
        );


        return $contents;
    }

    public function clearResponse()
    {

        $this->tagContents=[];
    }

    public function buildResponse($newResponse, $sourceTag = null)
    {
        $this->tagContents[]=$newResponse;

        $contents = LemurStr::cleanAndImplode($this->tagContents, $sourceTag);
        $this->tagContents=[];
        $this->tagContents[]=$contents;

        $this->getTagStack()->overWrite($this);
    }



    public function getTagContents()
    {
        return $this->tagContents;
    }

    public function setTagContents($value)
    {
        $this->tagContents[]=$value;
    }



    public function getResponseFromReParse($contents)
    {

        //does the contents actually have a tag?
        if (strpos($contents, "<")!==false) {
            //wrap it in template tags and reparse
            $contents = "<template>$contents</template>";
            $this->talkService->initFromTag($this->conversation, $contents, $this->tagName);
            $response = $this->talkService->parseDirect($contents);


            $reParsedConversation = $this->talkService->getConversation();
            //get all the debugArr and set it so we can see what happened
            $this->conversation->setDebug(
                'reparse',
                $this->talkService->responseOutput($reParsedConversation)['debugArr']
            );

            //copy local vars back for debugging
            //as we are still in the same turn carry all the vars over
            $vars = $reParsedConversation->getVars();
            foreach ($vars as $name => $value) {
                $this->conversation->setVar($name, $value);
            }

            return $response;
        } else {
            //this is just a simple string...
            //just return
            return $contents;
        }
    }

    public function getTagStack()
    {
        return TagStack::getInstance();
    }

    public function getUnknownValueStr($field)
    {

        return config('lemurbot.properties.unknown.'.$field);
    }
}
