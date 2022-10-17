<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;
use SimpleXMLElement;

/**
 * Class HtmlTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class HtmlTag extends AimlTag
{
    protected string $tagName = '';
    protected string $htmlTagType; //single e.g. <br/> or wrapper e.g. <b>bold</b>
    protected bool $allowHtml;
    //this is a standard tag
    static $aimlTagType = self::TAG_HTML;

    const HTMLTAG_SINGLE = 'single';
    const HTMLTAG_WRAPPED = 'wrapped';

    /**
     * HtmlTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     * @param string $tagName
     * @param string $htmlTagType
     */
    public function __construct(Conversation $conversation, array $attributes = [], $tagName, $htmlTagType)
    {
        parent::__construct($conversation, $attributes);
        $this->tagName = $tagName;
        $this->htmlTagType=$htmlTagType;
        $this->allowHtml = $conversation->getAllowHtml();



    }



    /**
     * @param $tagName
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }


    /**
     * We have encountered a closing html tag
     * Process the contents
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

        $aimlTag = mb_strtolower($this->tagName);


        if (!empty($this->allowHtml)) {
            if ($aimlTag!='') {
                if ($this->htmlTagType==HtmlTag::HTMLTAG_SINGLE) {

                    $contents = "<{$aimlTag}/>";
                    $this->buildResponse($contents);
                }
            }
        }

    }



    public function processContents($contents)
    {



        $aimlTag = mb_strtolower($this->tagName);
        //dd($contents,$this->tagName,$this->getAttributes());


        if (!empty($this->allowHtml)) {
            if ($aimlTag!='') {
                if ($this->htmlTagType==HtmlTag::HTMLTAG_WRAPPED) {
                    $contentsWithTags = <<<XML
<{$aimlTag}>$contents</{$aimlTag}>
XML;


                    //convert to xml
                    $aiml = new SimpleXMLElement($contentsWithTags);
                    //so we can add the tags in if needs be
                    if ($this->attributes) {
                        foreach ($this->attributes as $index => $value) {
                            $aiml->addAttribute(strtolower($index), $value);
                        }
                    }

                    $contents = trim(preg_replace('#<\?xml.*\?>#', '', $aiml->asXML()));
                } else {
                    $contents .= "<{$aimlTag}/>";
                }
            }
        }

        $this->buildResponse($contents);
    }
}
