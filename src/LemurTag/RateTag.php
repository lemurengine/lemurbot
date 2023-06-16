<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\BotRating;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class RateTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class RateTag extends AimlTag
{
    protected string $tagName = "Rate";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * Rate Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
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

        if ($this->hasAttributes()) {
            if ($this->hasAttribute('NAME')) {
                $feature = $this->getAttribute('NAME');
                if ($feature == 'count') {
                    $val = $this->conversation->bot->botRatingCount();
                    if (empty($val)) {
                        $val = '0';
                    }
                    $this->buildResponse($val);
                } elseif ($feature == 'average') {
                    $val = $this->conversation->bot->botRatingAvg();
                    if (empty($val)) {
                        $val = 'unrated';
                    }
                    $this->buildResponse($val);
                } elseif ($feature == 'max') {
                    $val = $this->conversation->bot->botRatingMax();
                    if (empty($val)) {
                        $val = 'unrated';
                    }
                    $this->buildResponse($val);
                } elseif ($feature == 'min') {
                    $val = $this->conversation->bot->botRatingMin();
                    if (empty($val)) {
                        $val = 'unrated';
                    }
                    $this->buildResponse($val);
                }
            }
        } else {
            //this is a set rating...
            $contents = $this->getCurrentTagContents(true);

            //we will only save numbers...
            if (is_numeric($contents)) {
                //reset if over the min or the max
                if ((float)$contents<=0) {
                    $contents=0;
                } elseif ((float)$contents>=5) {
                    $contents=5;
                }

                $rating = new BotRating();
                $rating->conversation_id=$this->conversation->id;
                $rating->bot_id=$this->conversation->bot->id;
                $rating->rating=$contents;
                $rating->save();
            }
        }
    }
}
