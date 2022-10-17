<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\CustomDoc;

/**
 * Class CustomDocRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version April 4, 2021, 9:42 am UTC
*/

class CustomDocRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'slug',
        'title',
        'body',
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
        return CustomDoc::class;
    }
}
