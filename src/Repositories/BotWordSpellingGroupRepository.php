<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\BotWordSpellingGroup;
use LemurEngine\LemurBot\Models\WordSpellingGroup;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BotWordSpellingGroupRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 12:52 pm UTC
*/

class BotWordSpellingGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'bot_id',
        'word_spelling_group_id',
        'slug',
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
        return BotWordSpellingGroup::class;
    }


    /**
     * Add extra data before saving
     *
     * @param array $input
     *
     * @return Model
     */
    public function bulkCreate($input)
    {

        $totalWordSpellingGroups = count($input['word_spelling_group_id']);

        for ($i=0; $i<$totalWordSpellingGroups; $i++) {
            //find the word spelling group
            $wordSpellingGroup = WordSpellingGroup::where('slug', $input['word_spelling_group_id'][$i])->first();

            //
            $linked = $input['linked'][$i];

            //we want to link this bot to this word spelling group
            if ($linked == 1) {
                //so lets update/restore or create a new BotWordSpellingGroup
                $item = BotWordSpellingGroup::withTrashed()->updateOrCreate([
                    'bot_id' => $input['bot_id'],
                    'word_spelling_group_id' => $wordSpellingGroup->id
                ]);
                if (!is_null($item->deleted_at)) {
                    $item->restore();
                }
            } else {
                //if the linked group exists then delete it
                BotWordSpellingGroup::where('bot_id', $input['bot_id'])
                    ->where('word_spelling_group_id', $wordSpellingGroup->id)
                    ->delete();
            }
        }

        return true;
    }
    /**
     * Add extra data before saving
     *
     * @param array $input
     *
     */
    public function createOrUpdate($input, $id = null):void
    {

        //we want to link this bot to this category group
        if (!is_null($id)) {
            //so lets update/restore or create a new BotWordSpellingGroup
            $item = BotWordSpellingGroup::withTrashed()->updateOrCreate([
                'bot_id' => $input['bot_id'],
                'word_spelling_group_id' => $input['word_spelling_group_id']
            ])->where('id', $id);
        } else {
            //so lets update or create a new BotWordSpellingGroup
            $item = BotWordSpellingGroup::withTrashed()->updateOrCreate([
                'bot_id' => $input['bot_id'],
                'word_spelling_group_id' => $input['word_spelling_group_id']
            ]);
        }

        //if deleted lets restore
        if (!is_null($item->deleted_at)) {
            $item->restore();
        }
    }
}
