<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class ATag
 * example: <img src="https://somepicture.com/this.jpg"/>
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class ImgTag extends HtmlTag
{
    protected string $tagName = 'Img';

    /**
     * Extends the default HTML tags
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes, $this->tagName, HtmlTag::HTMLTAG_WRAPPED);

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

        $attributes = $this->getAttributes();

        $content = "<img";

        if(!empty($attributes['SRC'])){
            $content .= " src=\"".$attributes['SRC']."\"";
        }

        if(!empty($attributes['WIDTH'])){
            $content .= " width=\"".$attributes['WIDTH']."\"";
        }

        if(!empty($attributes['HEIGHT'])){
            $content .= " height=\"".$attributes['HEIGHT']."\"";
        }

        if(!empty($attributes['STYLE'])){
            $content .= " style=\"".$attributes['STYLE']."\"";
        }

        if(!empty($attributes['CLASS'])){
            $content .= " class=\"".$attributes['CLASS']."\"";
        }

        if(!empty($attributes['ID'])){
            $content .= " id=\"".$attributes['ID']."\"";
        }

        $content .= " />";

        $this->buildResponse($content);

    }

}
