<?php

namespace LemurEngine\LemurBot\Repositories;

use LemurEngine\LemurBot\Models\User;

/**
 * Class UserRepository
 * @package LemurEngine\LemurBot\Repositories
 * @version December 31, 2020, 9:31 am UTC
*/

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'slug',
        'name',
        'email',
        'email_verified_at',
        'password',
        'api_token',
        'remember_token'
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
        return User::class;
    }
}
