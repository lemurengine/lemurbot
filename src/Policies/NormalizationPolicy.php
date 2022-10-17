<?php

namespace LemurEngine\LemurBot\Policies;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\Normalization;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class NormalizationPolicy extends MasterPolicy
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
        //only admins can access this model/action
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Normalization $normalization
     * @return mixed
     */
    public function view(User $user, Normalization $normalization)
    {
        //only admins can access this model/action
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
        //admins can do this but they should really do this
        //this is an action which happens when the user is talking to the bot
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Normalization $normalization
     * @return mixed
     */
    public function update(User $user, Normalization $normalization)
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
     * @param Normalization $normalization
     * @return mixed
     */
    public function delete(User $user, Normalization $normalization)
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
     * @param Normalization $normalization
     * @return mixed
     */
    public function restore(User $user, Normalization $normalization)
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
     * @param Normalization $normalization
     * @return mixed
     */
    public function forceDelete(User $user, Normalization $normalization)
    {
        //only admins can access this model/action
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}
