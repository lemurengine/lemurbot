<?php

namespace LemurEngine\LemurBot\Repositories;

use Exception;
use LemurEngine\LemurBot\Models\BotRating;

/**
 * Class BotRatingRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version April 7, 2021, 10:12 am UTC
*/

class BotRatingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'slug',
        'conversation_id',
        'bot_id',
        'rating'
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
        return BotRating::class;
    }

    /**
     * @param int $botId
     *
     * @throws Exception
     *
     * @return bool|mixed|null
     */
    public function deleteByBot($botId)
    {
        $query = $this->model->newQuery();

        return $query->where('bot_id', $botId)->delete();
    }
}
