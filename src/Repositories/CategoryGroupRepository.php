<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\CategoryGroup;

/**
 * Class CategoryGroupRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 7, 2021, 8:10 am UTC
*/

class CategoryGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'language_id',
        'section_id',
        'slug',
        'name',
        'description',
        'status',
        'is_master'
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
        return CategoryGroup::class;
    }
}
