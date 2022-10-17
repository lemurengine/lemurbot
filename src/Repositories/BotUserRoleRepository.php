<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\BotUserRole;

/**
 * Class UserRepository
*/

class BotUserRoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'role'
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
        return BotUserRole::class;
    }
}
