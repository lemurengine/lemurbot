<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\Set;
use LemurEngine\LemurBot\Models\SetValue;
use LemurEngine\LemurBot\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class SetValueRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 1:04 pm UTC
*/

class SetValueRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'set_id',
        'user_id',
        'slug',
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
        return SetValue::class;
    }
}
