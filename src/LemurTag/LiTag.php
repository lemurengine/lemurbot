<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use Exception;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class LiTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class LiTag extends AimlTag
{

    protected string $tagName = "Li";
    protected string $tag_id;
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * LiTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }


    /*
     * <template>
    <think>
        <set var='count_down'><star/></set>
    </think>
    <condition var='count_down'>
        <li value='5'><think><set var='count_down'>4</set></think>5<loop/></li>
        <li value='4'><think><set var='count_down'>3</set></think>4<loop/></li>
        <li value='3'><think><set var='count_down'>2</set></think>3<loop/></li>
        <li value='2'><think><set var='count_down'>1</set></think>2 <loop/></li>
        <li value='1'><think><set var='count_down'>0</set></think>1</li>
        <li></li>
    </condition>
</template>
     *
     */

    /**
     * nothing to do ...
     * this is intentionally left empty
     * @param $tagSettings
     * @return bool|void
     */
    public function openTag($tagSettings)
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

        $this->tagSettings=$tagSettings;
        $this->setAttribute('LI_STATUS', $this->getIfConditionIsTrue());
    }



    /**
     * just place the contents of <li></li> in the parent tag array
     * @return mixed
     * @throws Exception
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

        //we must get the parent <random> <tag> tag
        $parentObjectArr = $this->getPreviousTagByNames(['Random','Condition']);

        if(empty($parentObjectArr)){
            LemurLog::error(
                $this->getTagId(),
                "There should be a parent tag"
            );
            return '';
        }

        $parentObject = $parentObjectArr['tag'];
        $parentObjectIndex = $parentObjectArr['position'];
        $parentTagName = $parentObject->getTagName();

        $contents = $this->getCurrentTagContents(true);

        $isLoop = false;
        if ($parentObject->checkAttribute('TYPE', 'LOOP')) {
            $isLoop = true;
        }


        if ($isLoop) {
            //either this li or this condition is in a loop
            if (!$this->hasAttribute('VALUE')) {
                LemurLog::info(
                    $this->getTagId(),
                    "This is a closing tag li in condition loop and this is the default value"
                );
                //this is the default value!
                $this->buildResponse($contents);
            } elseif ($this->checkAttribute('LI_STATUS', 'false')) {
                LemurLog::info(
                    $this->getTagId(),
                    "This is a closing tag li in condition loop and the condition is false"
                );
                //nothing to do here...
            } else {
                LemurLog::info(
                    $this->getTagId(),
                    "This is a  closing tag li in condition loop and the condition is true"
                );
                $this->buildResponse($contents);
            }
        } elseif ($parentTagName == 'Random') {
            //the random stack has already been reduced...
            $this->buildResponse($contents);
        } elseif ($parentTagName == 'Condition') {

            $liValue = false; //this the default
            $conditionValue = false;

            //our li needs a value ...
            if ($this->hasAttribute('VALUE')) { //this item just has to exist
                $liValue = $this->getAttribute('VALUE','');
                $defaultValue = false;
            }else{
                $defaultValue = true;
            }
            if ($this->hasAttribute('NAME')) { //this item just has to exist
                $name = $this->getAttribute('NAME');
                $conditionValue = $this->conversation->getGlobalProperty($name, '');

            }elseif ($parentObject->hasAttribute('NAME')) { //this item just has to exist
                $name = $parentObject->getAttribute('NAME');
                $conditionValue = $this->conversation->getGlobalProperty($name, '');
            } elseif ($parentObject->hasAttribute('VAR')) {
                $name = $parentObject->getAttribute('VAR');
                $conditionValue = $this->conversation->getVar($name, '');
            }

            $liValue = str_replace("*","(.*)",$liValue);

            //so at this point we might have matched li will be our answer or we will have the default one remaining...
            //so if we have a match send it up the stack to the tempContents
            //and if we get the to default lets send if up the stack and only use if nothing exists
            if ($liValue === $conditionValue && !$parentObject->hasTmpContents()) {
                $parentObject->setTmpContents($contents);
            } elseif(preg_match('/^'.$liValue."$/i", $conditionValue, $matches)&& !$parentObject->hasTmpContents()) {
                $parentObject->setTmpContents($contents);
            } elseif(!$isLoop && $defaultValue && !$parentObject->hasTmpContents()) {
                $parentObject->setTmpContents($contents);
            }

            $this->getTagStack()->overWrite($parentObject, $parentObjectIndex);
        }
    }


    /**
     * this is the method that determines if a li condition is true and sets a flag in the li tag
     * @return string
     * @throws Exception
     */
    public function getIfConditionIsTrue()
    {

        $conditionObjectArr = $this->getPreviousTagByNames(['Condition']);

        if (!empty($conditionObjectArr)) {
            if (!$this->hasAttribute('VALUE')) {
                //this is a default value ...
                //so is always true...
                return 'true';
            }

            $conditionObject = $conditionObjectArr['tag'];

            $variableField = $conditionObject->getAttribute('VAR', '');
            if ($variableField == '') {
                $name = $conditionObject->getAttribute('NAME');
                $currentValue = $this->conversation->getGlobalProperty($name, '');
            } else {
                $name = $conditionObject->getAttribute('VAR');
                $currentValue = $this->conversation->getVar($name, '');
            }

            $valueToCheck = $this->getAttribute('VALUE');
            return (strtolower($currentValue) === strtolower($valueToCheck)?'true':'false');

        }

        return 'false';
    }
}
