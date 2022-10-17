<?php

namespace LemurEngine\LemurBot\Policies;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\WordSpelling;
use LemurEngine\LemurBot\Models\WordSpellingGroup;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class WordSpellingPolicy extends MasterPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //only admins can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param WordSpelling $wordSpelling
     * @return mixed
     */
    public function view(User $user, WordSpelling $wordSpelling)
    {
        //only admins can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //only admins can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param WordSpelling $wordSpelling
     * @return mixed
     */
    public function update(User $user, WordSpelling $wordSpelling)
    {
        //only admins can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param WordSpelling $wordSpelling
     * @return mixed
     */
    public function delete(User $user, WordSpelling $wordSpelling)
    {
        //only admins can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param WordSpelling $wordSpelling
     * @return mixed
     */
    public function restore(User $user, WordSpelling $wordSpelling)
    {
        //only admins can can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param WordSpelling $wordSpelling
     * @return mixed
     */
    public function forceDelete(User $user, WordSpelling $wordSpelling)
    {
        //only admins can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}
