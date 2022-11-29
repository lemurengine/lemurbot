<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\BotPlugin;

/**
 * Class BotPluginRepository
 * @package App\Repositories
 * @version November 28, 2022, 7:57 pm UTC
*/

class BotPluginRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'slug',
        'user_id',
        'bot_id',
        'plugin_id'
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
        return BotPlugin::class;
    }
}
