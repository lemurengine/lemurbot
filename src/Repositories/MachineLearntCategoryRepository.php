<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\Client;
use LemurEngine\LemurBot\Models\ClientCategory;
use LemurEngine\LemurBot\Models\MachineLearntCategory;
use LemurEngine\LemurBot\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class MachineLearntCategoryRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 21, 2021, 7:55 am UTC
*/

class MachineLearntCategoryRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'client_id',
        'bot_id',
        'turn_id',
        'slug',
        'pattern',
        'template',
        'topic',
        'that',
        'example_input',
        'example_output',
        'occurrences',
        'category_group_slug',
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
        return MachineLearntCategory::class;
    }
}
