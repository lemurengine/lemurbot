<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Category;

/**
 * Class CategoryRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 7, 2021, 5:47 pm UTC
*/

class CategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'category_group_id',
        'slug',
        'pattern',
        'regexp_pattern',
        'first_letter_pattern',
        'topic',
        'regexp_topic',
        'first_letter_topic',
        'that',
        'regexp_that',
        'first_letter_that',
        'template',
        'status'
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
        return Category::class;
    }
}
