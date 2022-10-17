<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\WordSpellingGroup;
use LemurEngine\LemurBot\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class WordSpellingGroupRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 1:08 pm UTC
*/

class WordSpellingGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'language_id',
        'user_id',
        'slug',
        'name',
        'description',
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
        return WordSpellingGroup::class;
    }
}
