<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\WordSpelling;
use LemurEngine\LemurBot\Models\WordSpellingGroup;
use LemurEngine\LemurBot\Models\WordTransformation;
use LemurEngine\LemurBot\Repositories\BaseRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class WordTransformationRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 1:09 pm UTC
*/

class WordTransformationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'language_id',
        'slug',
        'first_person_form',
        'second_person_form',
        'third_person_form',
        'third_person_form_female',
        'third_person_form_male',
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
        return WordTransformation::class;
    }
}
