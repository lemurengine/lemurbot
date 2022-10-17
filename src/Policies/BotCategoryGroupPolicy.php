<?php

namespace LemurEngine\LemurBot\Policies;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\BotCategoryGroup;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\Response;

class BotCategoryGroupPolicy extends MasterPolicy
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
     * @param BotCategoryGroup $botCategoryGroup
     * @return mixed
     */
    public function view(User $user, BotCategoryGroup $botCategoryGroup)
    {
        //only admins and owners can view the bot category groups
        return $this->checkIfAdminOrOwner($user, $botCategoryGroup);
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
     * @param BotCategoryGroup $botCategoryGroup
     * @return mixed
     */
    public function update(User $user, BotCategoryGroup $botCategoryGroup)
    {
        return $this->checkIfAdminOrOwner($user, $botCategoryGroup);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param BotCategoryGroup $botCategoryGroup
     * @return mixed
     */
    public function delete(User $user, BotCategoryGroup $botCategoryGroup)
    {
        return $this->checkIfAdminOrOwner($user, $botCategoryGroup);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param BotCategoryGroup $botCategoryGroup
     * @return mixed
     */
    public function restore(User $user, BotCategoryGroup $botCategoryGroup)
    {
        return $this->checkIfAdminOrOwner($user, $botCategoryGroup);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param BotCategoryGroup $botCategoryGroup
     * @return mixed
     */
    public function forceDelete(User $user, BotCategoryGroup $botCategoryGroup)
    {
        //only admins can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}
