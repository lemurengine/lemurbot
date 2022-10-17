<?php
namespace LemurEngine\LemurBot\LemurTag;

use Carbon\Carbon;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class DateTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class DateTag extends AimlTag
{
    protected string $tagName = "Date";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;
    /**
     * DateTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {


        parent::__construct($conversation, $attributes);

        //if there is no format set then set default format
        if (empty($attributes['FORMAT'])) {
            $this->setAttributes(['FORMAT'=>"%B %d %Y"]);
        }

        if (empty($attributes['LOCALE'])) {
            $this->setAttributes(['LOCALE'=>"en_US"]);
        }

        if (empty($attributes['TIMEZONE'])) {
            $this->setAttributes(['TIMEZONE'=>""]);
        }
    }




    public function closeTag()
    {

        $date = Carbon::now()->locale($this->getAttribute('LOCALE'));

        if (!empty($attributes['TIMEZONE'])) {
            $date->timezone($this->getAttribute('TIMEZONE'));
        }

        $tagContents = $date->formatLocalized($this->getAttribute('FORMAT'));

        $this->buildResponse($tagContents);
    }
}
