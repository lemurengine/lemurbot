<?php

namespace LemurEngine\LemurBot\Traits;

use LemurEngine\LemurBot\Classes\FlowStack;
use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Models\BotProperty;
use LemurEngine\LemurBot\Models\ConversationProperty;
use LemurEngine\LemurBot\Models\Turn;

trait ConversationHelper
{

    public function getVars()
    {
        return $this->vars;
    }

    public function getVar($name, $default = '')
    {
        if ($default=='') {
            $default = config('lemurbot.properties.unknown.var_property');
        }

        return $this->vars[$name]??$default;
    }

    public function setVar($name, $value)
    {
         $this->vars[$name]=$value;
    }

    public function getFeatures()
    {
        return $this->features;
    }

    public function getFeature($name, $default = '')
    {
        return $this->features[$name]??$default;
    }

    public function setFeature($name, $value)
    {
        $this->features[$name]=$value;
    }

    public function getDebugs()
    {
        return $this->debug;
    }

    public function debug($value, $message)
    {
        $this->setDebug($value, $message);
    }

    public function flow($key, $message)
    {

        FlowStack::getInstance()->push($key, $message);
    }


    public function setDebug($value, $message)
    {
        $this->debug[$value]=$message;
    }

    public function getAdminDebugs()
    {
        return $this->adminDebug;
    }

    public function setAdminDebug($value, $message)
    {
        $this->adminDebug[$value]=$message;
    }
    /**
     * show a count of the conversation turns
     *
     * @return int
     */
    public function getTurnCountAttribute()
    {

        return $this->humanTurns()->count();
    }



    public function currentConversationTurn()
    {
        return $this->hasOne(Turn::class, 'conversation_id')->latest('turns.id');
    }

    public function currentTurnId()
    {
        return $this->currentConversationTurn->id;
    }

    public function currentParentTurnId()
    {
        return ($this->currentConversationTurn->parent_turn_id??$this->currentConversationTurn->id);
    }

    public function previousConversationLog()
    {
        return $this->hasOne(Turn::class, 'conversation_id')->skip(1);
    }

    public static function pastHumanConversationTurn($conversationId, $skip)
    {
        return Turn::where('conversation_id', $conversationId)->where('turns.source',  'human')->latest('id')->skip($skip)->first();

    }
    public function getPluginTransformedInput()
    {
        return $this->currentConversationTurn->getPluginTransformedInput();
    }


    public function getSource()
    {
        return $this->currentConversationTurn->source;
    }

    public function getTopic()
    {
        return $this->getGlobalProperty('topic');
    }

    public function getInput($forceSource = false)
    {
        if ($forceSource === 'human') {
            //this is a v lazy way of doing this
            $turn = Turn::where('conversation_id', $this->id)->where('source', 'human')->latest('id')->skip(1)->first();
            if ($turn!==null) {
                $input = $turn->input;
            } else {
                $input = '';
            }

        } else {
            $input = $this->currentConversationTurn->input;
        }
        return $input;
    }


    public function getThat($forceSource = false)
    {
        $lastSource = $this->currentConversationTurn->source;

        if ($lastSource =='human' || $forceSource =='human') {
            //this is a v lazy way of doing this
            $turn = Turn::where('conversation_id', $this->id)->where('source', 'human')->latest('id')->skip(1)->first();
        } else {
            //this is a v lazy way of doing this
            $turn = Turn::where('conversation_id', $this->id)
                ->where('source', '!=', 'multiple')->latest('id')->skip(1)->first();
        }
        if ($turn!==null) {
            $output = $turn->output;
            $output = preg_replace('/<respondbutton[^>]*>([\s\S]*?)<\/respondbutton[^>]*>/', '', $output);
            $output = trim($output);
            $output = str_replace("<br/>",".", $output);
            $output = strip_tags($output, "<a>");
            $output = str_replace("..",".", $output);
            $allTurnSentences = LemurStr::splitIntoSentences($output);
            //now flip it as the last sentence = 1 (in AIML world)
            $allTurnSentences = array_reverse($allTurnSentences);

            if (!isset($allTurnSentences[0])) {
                $that = '';
            } else {
                $that = trim($allTurnSentences[0]);
            }
        } else {
            $that = '';
        }

        return $that;
    }


    public function normalisedInput()
    {
        return LemurStr::normalize($this->getInput(), true);
    }


    public function normalisedPluginTransformedInput()
    {
        return LemurStr::normalize($this->getPluginTransformedInput(), true);
    }



    public function normalisedTopic()
    {
        return LemurStr::normalize($this->getTopic(), true);
    }

    public function normalisedThat()
    {
        return LemurStr::normalize($this->getThat(), true);
    }


    public function getAllowHtml()
    {
        return $this->allow_html;
    }


    public function setTurnValue($key, $value)
    {
        $this->currentConversationTurn->$key = $value;
        $this->push();
    }

    public function completeTurn($value)
    {

        $currentStatus = $this->getTurnValue('status', 0, 'O');

        if ($currentStatus=='O') {
            $this->currentConversationTurn->status = $value;
            $this->currentConversationTurn->save();
            $this->push();
        }
    }

    public function getTurnValue($field, $skip = 1, $default = '')
    {
            //have had to use ..
            $res = Turn::where('conversation_id', $this->id)
                ->where('source', 'human')->latest('id')->skip($skip)->first()->$field;

            LemurLog::debug(
                __METHOD__,
                [
                    'conversation_id'=>$this->id,
                    'turn_id'=>$this->currentTurnId(),
                    'field'=>$field,
                    'skip'=>$skip,
                    'default'=>$default,
                    'found'=>$res
                ]
            );

        if (empty($res) && $res!==0 && $res!=='0') {
            return $default;
        }

            return $res;
    }



    public function isHumanTurn(){
        return ($this->currentConversationTurn->source==='human'?true:false);
    }






    public function getBotProperty($name)
    {
        return BotProperty::where('bot_id', $this->bot->id)->where('name', $name)->first();
    }



    public function getBotPropertyValue($name, $default = false)
    {

        $botProperty = BotProperty::where('bot_id', $this->bot->id)->where('name', $name)->first('value');
        if($botProperty === null && !$default){
            return config('lemurbot.properties.unknown.bot_property');
        }elseif($botProperty === null && $default){
            return $default;
        }else{
            return $botProperty->value;
        }

    }




    public function setGlobalProperty($name, $value)
    {

        ConversationProperty::updateOrCreate(
            ['conversation_id' =>  $this->id, 'name'=>$name],
            ['conversation_id' =>  $this->id, 'name'=>$name, 'value'=>$value]
        );
    }

    public function getGlobalProperty($name, $default = '')
    {

        $property = ConversationProperty::where('conversation_id', $this->id)->where('name', $name)->first();
        if ($property===null) {
            if ($default=='') {
                $default = config('lemurbot.properties.unknown.global_property');
            }

            return $default;
        }
        return $property->value;
    }

    public function getGlobalProperties($convertJsonToArray=false)
    {

        $items = ConversationProperty::select(['name', 'value'])
            ->where('conversation_id', $this->id)->pluck('value', 'name')->toArray();

        if($convertJsonToArray){
            foreach($items as $name => $value){
                if($this->isJson($value)){
                    $items[$name] = json_decode($value);
                }
            }
        }

        return $items;
    }

    public function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
