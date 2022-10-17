<?php

namespace LemurEngine\LemurBot\Policies;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\WordTransformation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class WordTransformationPolicy extends MasterPolicy
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
        //only admins can can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param WordTransformation $wordTransformation
     * @return mixed
     */
    public function view(User $user, WordTransformation $wordTransformation)
    {
        //only admins can can do this
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
        //only admins can can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param WordTransformation $wordTransformation
     * @return mixed
     */
    public function update(User $user, WordTransformation $wordTransformation)
    {
        //only admins can can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param WordTransformation $wordTransformation
     * @return mixed
     */
    public function delete(User $user, WordTransformation $wordTransformation)
    {
        //only admins can can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param WordTransformation $wordTransformation
     * @return mixed
     */
    public function restore(User $user, WordTransformation $wordTransformation)
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
     * @param WordTransformation $wordTransformation
     * @return mixed
     */
    public function forceDelete(User $user, WordTransformation $wordTransformation)
    {
        //only admins can can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}
