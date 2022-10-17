<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Bot;

/**
 * Class BotRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 12:47 pm UTC
*/

class BotRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'slug',
        'language_id',
        'user_id',
        'name',
        'summary',
        'description',
        'default_response',
        'critical_category_group',
        'lemurtar_url',
        'image',
        'status',
        'is_public'
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
        return Bot::class;
    }
}
