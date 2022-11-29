<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Plugin;

/**
 * Class PluginRepository
 * @package App\Repositories
 * @version November 28, 2022, 7:56 pm UTC
*/

class PluginRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'slug',
        'title',
        'description',
        'classname',
        'apply_plugin',
        'return_onchange',
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
        return Plugin::class;
    }
}
