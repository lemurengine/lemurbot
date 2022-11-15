<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\WordSpelling;
use LemurEngine\LemurBot\Models\WordSpellingGroup;
use LemurEngine\LemurBot\Repositories\BaseRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class WordSpellingRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 1:08 pm UTC
*/

class WordSpellingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'word_spelling_group_id',
        'slug',
        'word',
        'replacement'
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
        return WordSpelling::class;
    }



    /**
     * Add extra data before saving
     *
     * @param array $input
     *
     */
    public function createOrUpdate($input, $id = null):void
    {

        if (!is_null($id)) {
            //so lets update/restore or create a new BotWordSpellingGroup
            $item = WordSpelling::withTrashed()->updateOrCreate(
                [
                    'word_spelling_group_id' => $input['word_spelling_group_id'],
                    'word' => $input['word']],
                [
                    'user_id' => Auth::id(),
                    'replacement' => $input['replacement']
                ]
            )->where('id', $id);
        } else {
            //so lets update or create a new BotWordSpellingGroup
            $item = WordSpelling::withTrashed()->updateOrCreate([
                'word_spelling_group_id' => $input['word_spelling_group_id'],
                'word' => $input['word']],
                [
                    'user_id' => Auth::id(),
                    'replacement' => $input['replacement']
                ]);
        }

        //if deleted lets restore
        if (!empty($item->deleted_at) && !is_null($item->deleted_at)) {
            $item->restore();
        }
    }
}
