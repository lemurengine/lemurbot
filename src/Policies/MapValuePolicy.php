<?php

namespace LemurEngine\LemurBot\Policies;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\Map;
use LemurEngine\LemurBot\Models\MapValue;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MapValuePolicy extends MasterPolicy
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
     * @param MapValue $mapValue
     * @return mixed
     */
    public function view(User $user, MapValue $mapValue)
    {
        //The user can view this if the item is created by them or the parent map is_master = true
        //or if they are admin.
        //admin can do anything
        $map = Map::find($mapValue->map_id);
        return $this->checkIfAdminOrOwner($user, $mapValue, false, $map, true);
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
     * @param MapValue $mapValue
     * @return mixed
     */
    public function update(User $user, MapValue $mapValue)
    {
        //The user can update their own item
        return $this->checkIfAdminOrOwner($user, $mapValue);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param MapValue $mapValue
     * @return mixed
     */
    public function delete(User $user, MapValue $mapValue)
    {
        //The user can delete their own item
        return $this->checkIfAdminOrOwner($user, $mapValue);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param MapValue $mapValue
     * @return mixed
     */
    public function restore(User $user, MapValue $mapValue)
    {
        //The user can restore their own item
        return $this->checkIfAdminOrOwner($user, $mapValue);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param MapValue $mapValue
     * @return mixed
     */
    public function forceDelete(User $user, MapValue $mapValue)
    {
        //only admins can access this model/action
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}
