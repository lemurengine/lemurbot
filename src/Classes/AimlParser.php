<?php

namespace LemurEngine\LemurBot\Classes;

use LemurEngine\LemurBot\Factories\AimlTagFactory;
use LemurEngine\LemurBot\Models\Category;
use LemurEngine\LemurBot\Models\Conversation;
use LemurEngine\LemurBot\Models\Wildcard;
use Exception;


class AimlParser
{

    private $xmlParser;
    private $currentTag;
    private $response='';
    private $conditionStack = [];
    private $conversation;
    private $currentTurn;
    private $category;




    public function setConversation(Conversation $conversation)
    {
        $this->conversation = $conversation;
        $this->currentTurn = $this->conversation->currentConversationTurn;
    }

    public function resetResponse()
    {
        $this->response = '';
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function expandWhiteSpaceTagSpacing($template)
    {
        LemurLog::debug(
            'expandWhiteSpaceTagSpacing',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'template'=>$template,
            ]
        );
        $newTemplate = preg_replace('~\s(<star[^>]*>)~iU', "<whitespace />$1", $template);
        $newTemplate = preg_replace('~(<star[^>]*>)\s~iU', "$1<whitespace />", $newTemplate);

        $newTemplate = preg_replace('~\s(<topicstar[^>]*>)~iU', "<whitespace />$1", $newTemplate);
        $newTemplate = preg_replace('~(<topicstar[^>]*>)\s~iU', "$1<whitespace />", $newTemplate);


        $newTemplate = preg_replace('~\s(<thatstar[^>]*>)~iU', "<whitespace />$1", $newTemplate);
        $newTemplate = preg_replace('~(<thatstar[^>]*>)\s~iU', "$1<whitespace />", $newTemplate);

        if($newTemplate !=$template){
            $this->conversation->flow('expanding_whitespace', $newTemplate);
        }
        LemurLog::debug(
            'expandWhiteSpaceTagSpacing',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'template'=>$template,
            ]
        );
        return $newTemplate;

    }

    /**
     * before we parse the AIML template
     * we need to do some things such as
     * extract the data from the wildcards in the pattern/input
     */
    public function extractAllWildcards()
    {
        LemurLog::debug(
            'extractAllWildcards',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
            ]
        );

        $this->extractAndSaveWildCards('star');
        $this->extractAndSaveWildCards('topicstar');
        $this->extractAndSaveWildCards('thatstar');

        LemurLog::debug(
            'extractAllWildcards end',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
            ]
        );
    }

    public function parseTemplate($encoding = 'UTF-8', $is_final = false)
    {

        return $this->parse($this->category->template, $encoding, $is_final);
    }

    public function parse($template, $encoding = 'UTF-8', $is_final = false)
    {

        LemurLog::info(
            'parsing',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'template'=>htmlentities($template)
            ]
        );

        $this->conversation->debug('parsing.matched.template', $template);

        $this->setXmlParser($encoding);


        $template = preg_replace('/(\r\n|\r|\n)+/', "\n", $template);
        // Replace whitespace characters with a single space
        $template = preg_replace('/\s+/', ' ', $template);
        $template = trim($template);

        $template = "<template>$template</template>";

        if ($template == '<template></template>') {
            $this->conversation->debug('parsing.blank.template', htmlentities($template));
            $this->response='';
            return $this->response;
        }
        $template = $this->expandTags($template);
        $template = $this->reduceRandomStack($template);
        $this->setConditionStack($template);
        xml_parse($this->xmlParser, $template, $is_final);
        $this->conversation->debug('output.parsed', $this->response);


        LemurLog::info(
            'parsing end',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId()
            ]
        );

        return $this->response;
    }

    public function setConditionStack($template)
    {

        $template = LemurStr::removeTrailingKeepSpace($template);
        $xml_data = simplexml_load_string($template);


        if (isset($xml_data->condition)) {
            foreach ($xml_data->condition as $cs) {
                $this->conditionStack[] = $cs->asXML();
            }
        }
    }

    public function expandTags($template){

        $oTemplate = $template;

        $template = str_replace("<sr/>", "<srai><star /></srai>", $template);
        $template = str_replace("<sr />", "<srai><star /></srai>", $template);
        $template = str_replace("<person2/>", "<person2><star /></person2>", $template);
        $template = str_replace("<person2 />", "<person2><star /></person2>", $template);
        $template = str_replace("<person/>", "<person><star /></person>", $template);
        $template = str_replace("<person />", "<person><star /></person>", $template);

        //todo decide if i want to kepp

      /*  $template = str_replace('<set><name>', '<set name=\'', $template);
        $template = str_replace('<get><name>', '<get name=\'', $template);
        $template = str_replace('<map><name>', '<map name=\'', $template);
        $template = str_replace('</name>', '\'>', $template); */


        if( $oTemplate !== $template){
            $this->conversation->debug('expanded.template', htmlentities($template));
        }

        return $template;

    }

    public function reduceRandomStack($template)
    {
        $newTemplate = LemurStr::removeTrailingKeepSpace($template);
        $originalTemplate = $xmlTemplate = simplexml_load_string($newTemplate);
        if (isset($xmlTemplate->random[0]->li)) { //there is atleast 1
            $totalRandomNodesInTemplate = count($xmlTemplate->random);
            for($r=0; $r<$totalRandomNodesInTemplate; $r++){
                $totalLiNodesInRandomTag = count($xmlTemplate->random[$r]->li);
                $rand = rand(0,$totalLiNodesInRandomTag-1);
                $randomStack[] = "<placeholder>".$xmlTemplate->random[$r]->li[$rand]->asXML()."</placeholder>";
            }
            foreach($randomStack as $index=> $randomToReplace){
                $xmlRandomLi = simplexml_load_string($randomToReplace);
                $domToChange = dom_import_simplexml($xmlTemplate->random);
                $domReplace  = dom_import_simplexml($xmlRandomLi);
                $nodeImport  = $domToChange->ownerDocument->importNode($domReplace, TRUE);
                $domToChange->parentNode->replaceChild($nodeImport, $domToChange);

            }
            if ($originalTemplate->asXML() != $xmlTemplate->asXML()) {
                $this->conversation->debug('template.reduction.' . $r, $xmlTemplate->asXML());
                $this->conversation->flow('reducing_randomstack', $xmlTemplate->asXML());
            }
        }

        $strXml = $xmlTemplate->asXML();
        $strXml = $this->removeStrXml("</li></placeholder>", $strXml);
        $strXml = $this->removeStrXml("<placeholder><li>", $strXml);
        return trim($this->removeStrXml('<?xml version="1.0"?>', $strXml));


    }

    public function removeStrXml($toRemove, $xmlString){
        return str_replace($toRemove,"",$xmlString);
    }


    public function getConditionStack()
    {
        return $this->conditionStack;
    }

    public function setXmlParser($encoding)
    {

        $this->xmlParser = xml_parser_create($encoding);
        xml_set_object($this->xmlParser, $this);
        xml_set_element_handler($this->xmlParser, 'startElement', 'endElement');
        xml_set_character_data_handler($this->xmlParser, 'cdata');
    }

    public function getXmlParser()
    {
        return $this->xmlParser;
    }

    public function startElement($parser, $name, array $attributes)
    {
        //clean the tag name
        $tagName = $this->cleanTagClassName($name);
        //set the tag class
        $this->setTagClass($tagName, $attributes);

        //initialise
        $tagSettings=array();

        //if this is a condition tag then set some extra bits if the condition is right
        //liz need to check if this can be safely removed
        /*if ($name=='CONDITION') {
            $raw_XML = $this->conditionStack[$this->conditionPointer];
            if (strpos($raw_XML, '<loop/>') !== false) {
                $tagSettings['raw_xml'] = $raw_XML;
                $tagSettings['is_loop'] = 1;
            }
            //increase the index count
            $this->conditionPointer++;
        }*/

        //if this is a template tag and we are not in learning mode then increase the tag stack index
        if ($name=='TEMPLATE' && !$this->currentTag->isInLearningMode()) {
                $this->getTagStack()->incIndex($this->getTagId());
        }


        $this->currentTag->openTag($tagSettings);

        LemurLog::info(
            'start',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->currentTag->getTagId()
            ]
        );
        $this->getTagStack()->push($this->currentTag, $this->getTagId());

        if (!$this->currentTag->getIsTagValid()) {
            LemurLog::warn(
                'Invalid tag',
                [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId()
                ]
            );
        } else {
            //check if parent tag is valid or not
            $this->checkSetParentTagIsInvalid();
        }
    }

    public function setTagClass($tagName, $attributes)
    {
        $this->currentTag =  AimlTagFactory::create($this->conversation, $tagName, $attributes);
    }

    public function getTagClass()
    {
        return $this->currentTag;
    }

    public function getTagStack()
    {
        return TagStack::getInstance();
    }


    public function cdata($parser, $cdata)
    {

        LemurLog::debug(
            'Processing cdata',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId()
            ]
        );
        if ($this->currentTag->getIsTagValid()) {
            if ($this->currentTag->getTagName()=='Template') {
               // echo "<br/>THIS IS JUST CDATA...".$cdata." parent = ".$p->getTagName();
                $this->currentTag->processContents($cdata);
            } else {
               // echo "<br/>THIS IS JUST PROCESS CONTENTS...".htmlentities($cdata)." parent = ".$p->getTagName();
                $this->currentTag->processContents($cdata);
            }
        } else {
            LemurLog::warn(
                'Invalid tag ignoring contents',
                [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId()
                ]
            );
        }
    }

    public function endElement($parser, $name)
    {


        LemurLog::debug(
            'endElement',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId()
            ]
        );

        $count = 0;
        $setResponse = false;

        if ($this->currentTag->getIsTagValid()) {
            $currentResponse = $this->currentTag->closeTag();

            if ($currentResponse!='') {
                $setResponse=true;
                $this->setResponse($currentResponse);
            }
        } else {
            $currentResponse='';

            LemurLog::warn(
                'Invalid tag',
                [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId()
                ]
            );
        }

        //if we have a parent tag open lets send the parsed value to that tag
        if (!$this->getTagStack()->isFinalTag()) {
            $currentResponse = $this->currentTag->getTagContentsCompact();
            $this->getTagStack()->pop();
        }

        if ($name=='TEMPLATE' && !$this->currentTag->isInLearningMode()) {
            $this->getTagStack()->decIndex($this->getTagId());
        }

        $this->currentTag = $this->getTagStack()->lastItem();
        if ($this->currentTag) {
            if (!$setResponse) {
                //$this->setResponse($currentResponse, 'parts');
                $this->currentTag->buildResponse($currentResponse);
            }
        }
    }




    public function setResponse($previousTagContents)
    {

        LemurLog::info(
            'setting response',
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->currentTag->getTagId(),
                'previousContents'=>$previousTagContents,
                'currentStack'=>$this->currentTag->getTagStack(),
            ]
        );


        $this->response .= $previousTagContents;
    }

    public function getTagId()
    {
        return $this->currentTag->getTagId();
    }

    public function __destruct()
    {

        if (is_resource($this->xmlParser) && is_object($this->currentTag) && !$this->currentTag->isInLearningMode()) {
            xml_parser_free($this->xmlParser);
        }
    }

    public function cleanTagClassName($name)
    {

        $name = preg_replace('/_+|\s+/', ' ', $name);
        $name = ucwords(mb_strtolower($name));
        return str_replace(' ', '', $name);
    }




    public function getResponse()
    {


        return $this->response;
    }

    /**
     * if the parent tag is invalid then we have to invalidate the current tag
     */
    public function checkSetParentTagIsInvalid()
    {

        $previousTag = $this->getTagStack()->previousItemByTagId($this->getTagId());


        if ($previousTag && !$previousTag->getIsTagValid()) {
            $this->currentTag->setIsTagValid(false);
            LemurLog::warn('Invalid tag', [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId()
            ]);
        } elseif ($previousTag && $previousTag->getIsTagValid()) {
            LemurLog::info('Parent tag is valid', [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->currentTag->getTagId(),
            ]);
        } else {
            LemurLog::warn('no previous tag', [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId()
            ]);
        }
    }




    public function getCurrentTag()
    {
        return $this->currentTag;
    }

    public function extractAndSaveWildCards($extractType)
    {


        switch ($extractType) {
            case 'star':
                $str = $this->conversation->getInput();
                $strRegExp = $this->category->regexp_pattern;
                break;
            case 'topicstar':
                $str = $this->conversation->getTopic();
                $strRegExp = $this->category->regexp_topic;
                break;
            case 'thatstar':
                $str = $this->conversation->getThat();
                $strRegExp = $this->category->regexp_that;
                break;
            default:
                throw new Exception("Unable to determine wildcard subject");
                break;
        }


        $wildcardArr = LemurStr::extractWildcardFromString($str, $strRegExp);


        if (!empty($wildcardArr)) {
            //reverse it so we maintain the order as we insert...
            $wildcardArr = array_reverse($wildcardArr);
            foreach ($wildcardArr as $wildCard) {
                $wildcard = new Wildcard;
                $wildcard->conversation_id = $this->conversation->id;
                $wildcard->type = $extractType;
                $wildcard->value = LemurStr::cleanKeepSpace($wildCard);
                $wildcard->save();
                $this->conversation->flow('wildcard.'.$extractType, LemurStr::cleanKeepSpace($wildCard));
            }
        }
    }

    public function pushCurrentTagToTagStack()
    {
        $this->getTagStack()->push($this->currentTag, $this->getTagId());
    }

    public function initTemplateTagOnTagStack()
    {
        $this->getTagStack()->incIndex($this->getTagId());
    }
}
