<?php

namespace LemurEngine\LemurBot\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use LemurEngine\LemurBot\Models\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClientRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version January 6, 2021, 12:56 pm UTC
*/

class ClientRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bot_id',
        'slug',
        'is_banned'
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
        return Client::class;
    }

    /**
     * Update model record for given id
     *
     * @param array $input
     * @param int $id
     *
     * @return Builder|Builder[]|Collection|Model
     */
    public function update($input, $id)
    {
        //You can only edit the is_banned field so unset everything else
        if (isset($input['is_banned'])) {
            $newInput['is_banned'] = $input['is_banned'];
        } else {
            $newInput['is_banned'] = 0;
        }

        return parent::update($newInput, $id);
    }
}
