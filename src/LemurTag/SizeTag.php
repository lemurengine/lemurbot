<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Category;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class SizeTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class SizeTag extends AimlTag
{
    protected string $tagName = "Size";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;


    /**
     * SizeTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }


    public function getTotalCategories()
    {

        $botId = $this->conversation->bot->id;

        $sqlBuilder = Category::select(
            'categories.id',
            'categories.slug',
            'categories.pattern',
            'categories.regexp_pattern',
            'categories.first_letter_pattern',
            'categories.topic',
            'categories.regexp_topic',
            'categories.first_letter_topic',
            'categories.that',
            'categories.regexp_that',
            'categories.first_letter_that',
            'categories.template'
        )
            ->join('category_groups', 'category_groups.id', '=', 'categories.category_group_id')
            ->join('bot_category_groups', function ($join) use ($botId) {
                $join->on('category_groups.id', '=', 'bot_category_groups.category_group_id')
                    ->where('bot_category_groups.bot_id', $botId);
            })
            ->where('categories.status', 'A')
            ->where('category_groups.status', 'A');

        return $sqlBuilder->count();
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
        $tagContents = $this->getTotalCategories();
        $this->buildResponse($tagContents);
    }
}
