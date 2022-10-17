<?php

namespace LemurEngine\LemurBot\Policies;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\Category;
use LemurEngine\LemurBot\Models\CategoryGroup;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy extends MasterPolicy
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
     * @param Category $category
     * @return mixed
     */
    public function view(User $user, Category $category)
    {
        $categoryGroup = CategoryGroup::find($category->category_group_id);
        return $this->checkIfAdminOrOwner($user, $category, true, $categoryGroup, true);
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
     * @param Category $category
     * @return mixed
     */
    public function update(User $user, Category $category)
    {
        return $this->checkIfAdminOrOwner($user, $category);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Category $category
     * @return mixed
     */
    public function delete(User $user, Category $category)
    {
        return $this->checkIfAdminOrOwner($user, $category);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Category $category
     * @return mixed
     */
    public function restore(User $user, Category $category)
    {
        return $this->checkIfAdminOrOwner($user, $category);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Category $category
     * @return mixed
     */
    public function forceDelete(User $user, Category $category)
    {
        //only admins can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}
