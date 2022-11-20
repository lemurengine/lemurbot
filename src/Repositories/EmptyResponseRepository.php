<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\EmptyResponse;

/**
 * Class EmptyResponseRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 1:01 pm UTC
*/

class EmptyResponseRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'slug',
        'that',
        'input',
        'source',
        'occurrences'
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
        return EmptyResponse::class;
    }
}
