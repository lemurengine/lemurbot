<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Wildcard;
use LemurEngine\LemurBot\Repositories\BaseRepository;

/**
 * Class WildcardRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version March 26, 2021, 7:11 am UTC
*/

class WildcardRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'conversation_id',
        'slug',
        'type',
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
        return Wildcard::class;
    }
}
