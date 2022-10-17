<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\BotAllowedSite;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BotAllowedSiteRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 21, 2021, 7:55 am UTC
*/

class BotAllowedSiteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'user_id',
        'slug',
        'website_url'
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
        return BotAllowedSite::class;
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return Model
     */
    public function update($input, $id)
    {

        //we never update the bot or the property name ...
        //we can only update the value
        unset($input['bot_id']); //this cannot be updated

        return parent::update($input, $id);
    }

    /**
     * Add extra data before saving
     *
     * @param array $input
     *
     * @return Model
     */
    public function create($input)
    {
        //so lets update or create a new BotCategoryGroup
        $item = BotAllowedSite::withTrashed()->updateOrCreate([
            'bot_id' => $input['bot_id'],
            'website_url' => $input['website_url']
        ]);

        //if deleted lets restore
        if (!is_null($item->deleted_at)) {
            $item->restore();
        }

        return $item;
    }
}
