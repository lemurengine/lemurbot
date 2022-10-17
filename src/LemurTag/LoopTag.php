<?php
namespace LemurEngine\LemurBot\LemurTag;

use Exception;
use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class LoopTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class LoopTag extends LiTag
{
    protected string $tagName = "Loop";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * LoopTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }




    /**
     * when the loop tag is closed....
     * we need to tell the parent li this is a loop
     * and let the run while the condition is true
     * so find the parent li and set the isLoop flag to true
     *
     * <template>
     * <think><set name='count'>0</set></think>
     * <condition name='count'>
     * <li><value><star/></value></li>
     * <li><set name='count'><map><name>test_loop</name><get name='count'/></map></set><loop/></li>
     * </condition>
     * </template>
     *
     *
     *
     *
     *
     *
     * <template>
     * <think><set var='count_down'><star/></set></think>
     * <condition var='count_down'>
     * <li value='5'><think><set var='count_down'>4</set></think>5 <loop/></li>
     * <li value='4'><think><set var='count_down'>3</set></think>4 <loop/></li>
     * <li value='3'><think><set var='count_down'>2</set></think>3 <loop/></li>
     * <li value='2'><think><set var='count_down'>1</set></think>2 <loop/></li>
     * <li value='1'><think><set var='count_down'>0</set></think>1 </li>
     * <li></li>
     * </condition>
     * </template>
     *
     *
     *
     *
     *
     * we hit the loop tag
     * is the parent li tag condition met?
     * if so display...
     * add it to the condition li array ....
     * and then process the next
     *
     *
     *
     * @return string|void
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

        $conditionObjectArr = $this->getPreviousObject('Condition');
        $conditionObjectTag = $conditionObjectArr['tag'];
        $conditionObjectIndex = $conditionObjectArr['index'];


        //get the previous Li object
        $liObjectArr = $this->getPreviousObject('Li');
        $liObjectTag = $liObjectArr['tag'];
        $liObjectIndex = $liObjectArr['index'];

        $liObjectTag->setAttribute('TYPE', 'LOOP');
        $conditionObjectTag->setAttribute('TYPE', 'LOOP');
        $this->getTagStack()->overWrite($liObjectTag, $liObjectIndex);
        $this->getTagStack()->overWrite($conditionObjectTag, $conditionObjectIndex);
    }





    /**
     *
     *
     *
     * <category>
        <pattern>NTH <set>number</set> *</pattern>
        <template>
        <think>
            <set name="nth"><star/></set>
            <set name="count">1</set>
            <set name="letters"><explode><star index="2"/></explode></set>
        </think>
        <loop>
            <li><name>letters</name><value>undefined</value><star index="2"/> has only
                   <map><name>predecessor</name><get name="count"/></map> letters.</li>
            <li><name>count</name><value><get name="nth"/></value>The
                   <map><name>number2ordinal</name><star/></map> letter is
                    <srai>FIRSTLETTER <get name="letters"/></srai></li>
            <li>
                <think>
                    <set name="count"><map><name>successor</name><get name="count"/></map></set>
                    <set name="letters"><srai>REMAININGLETTERS <get name="letters"/></srai></set>
                </think>
            <li>
        </loop>
        </template>
        </category>
     *
     *
     *
     */



    /**
     * move through the tag stack and look for the first random or the first condition
     *
     * @return mixed
     */
    public function getParentConditionOrRandomObject()
    {

        //todo get this generalised (the whole method)
        $previousMaxIndex = $this->getTagStack()->maxPosition()-1;

        for ($i=$previousMaxIndex; $i>=0; $i--) {
            $inStackTagName = $this->getTagStack()->item($i)->getTagName();

            if ($inStackTagName=='Random'|| $inStackTagName=='Condition') {
                return ['index'=>$i, 'tag'=>$this->getTagStack()->item($i)];
                //return $this->getTagStack()->item($i);
            }
        }


        //todo nothing is here
        return false;
    }


    /**
     * move through the tag stack and look for the first random or the first condition
     *
     * @param $tagName
     * @return mixed
     * @throws Exception
     */
    public function getPreviousObject($tagName)
    {

        //todo get this generalised (the whole method)
        $previousMaxIndex = $this->getTagStack()->maxPosition()-1;

        for ($i=$previousMaxIndex; $i>=0; $i--) {
            $inStackTagName = $this->getTagStack()->item($i)->getTagName();

            if ($inStackTagName==$tagName) {
                return ['index'=>$i, 'tag'=>$this->getTagStack()->item($i)];
                //return $this->getTagStack()->item($i);
            }
        }


        //todo nothing is here
        return false;
    }
}
