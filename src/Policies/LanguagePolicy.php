<?php

namespace LemurEngine\LemurBot\Policies;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\Language;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LanguagePolicy extends MasterPolicy
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
        //anyone can view a list of bots
        //the query will limit the users
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Language $language
     * @return mixed
     */
    public function view(User $user, Language $language)
    {
        //anyone can view a list of bots
        //the query will limit the users
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //only admins can access this model/action
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Language $language
     * @return mixed
     */
    public function update(User $user, Language $language)
    {
        //only admins can access this model/action
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Language $language
     * @return mixed
     */
    public function delete(User $user, Language $language)
    {
        //only admins can access this model/action
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Language $language
     * @return mixed
     */
    public function restore(User $user, Language $language)
    {
        //only admins can access this model/action
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Language $language
     * @return mixed
     */
    public function forceDelete(User $user, Language $language)
    {
        //only admins can access this model/action
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}
