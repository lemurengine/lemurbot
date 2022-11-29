<?php

namespace LemurEngine\LemurBot\Policies;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\Plugin;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PluginPolicy extends MasterPolicy
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
     * @param Plugin $plugin
     * @return mixed
     */
    public function view(User $user, Plugin $plugin)
    {
        //The user can view this if the item is created by them or is_master = true
        //or if they are admin.
        //admin can do anything
        return $this->checkIfAdminOrOwner($user, $plugin, true);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //anyone can create an items for themselves
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Plugin $plugin
     * @return mixed
     */
    public function update(User $user, Plugin $plugin)
    {
        //The user can update their own item
        return $this->checkIfAdminOrOwner($user, $plugin);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Plugin $plugin
     * @return mixed
     */
    public function delete(User $user, Plugin $plugin)
    {
        //The user can delete their own item
        return $this->checkIfAdminOrOwner($user, $plugin);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Plugin $plugin
     * @return mixed
     */
    public function restore(User $user, Plugin $plugin)
    {
        //The user can restore their own item
        return $this->checkIfAdminOrOwner($user, $plugin);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Plugin $plugin
     * @return mixed
     */
    public function forceDelete(User $user, Plugin $plugin)
    {
        //only admins can access this model/action
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}
