<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\Normalization;
use LemurEngine\LemurBot\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

/**
 * Class NormalizationRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 1:02 pm UTC
*/

class NormalizationRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'language_id',
        'slug',
        'original_value',
        'normalized_value'
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
        return Normalization::class;
    }
}
