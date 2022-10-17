<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class ConversationRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 12:58 pm UTC
*/

class ConversationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'slug',
        'bot_id',
        'client_id',
        'allow_html'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Conversation::class;
    }
}
