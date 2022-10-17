<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Turn;
use LemurEngine\LemurBot\Repositories\BaseRepository;

/**
 * Class TurnRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 13, 2021, 2:03 pm UTC
*/

class TurnRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'conversation_id',
        'client_category_id',
        'category_id',
        'parent_turn_id',
        'slug',
        'input',
        'output',
        'status',
        'source',
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
        return Turn::class;
    }
}
