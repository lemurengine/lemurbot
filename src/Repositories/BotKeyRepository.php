<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\BotKey;

/**
 * Class BotKeyRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version April 4, 2021, 9:42 am UTC
*/

class BotKeyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'user_id',
        'slug',
        'name',
        'description',
        'value'
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
        return BotKey::class;
    }
}
