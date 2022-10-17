<?php

namespace LemurEngine\LemurBot\Policies;

use Illuminate\Auth\Access\Response;
use LemurEngine\LemurBot\Facades\LemurPriv;

class MasterPolicy
{

    /**
     *
     * lets check if the item an admin then they can do this... admins can do anything
     *
     * If the logged in user created the item they can edit it
     *
     * If the model is_master then they are allowed (in general this is just for viewing)
     *
     * If the parentModel is_master then they are allowed (in general this is just for viewing)
     *
     *
     * @param $user
     * @param $model
     * @param string $message
     * @param bool $hasMaster
     * @param $parentModel
     * @param bool $hasParentMaster
     * @return mixed
     */
    public function checkIfAdminOrOwner(
        $user,
        $model,
        $hasMaster = false,
        $parentModel = null,
        $hasParentMaster = false,
        $message = 'You cannot perform this action.'
    ) {


      //  dd(LemurPriv::isAdmin($user), LemurPriv::isAuthor($user),$hasMaster,$model->is_master );

        if (LemurPriv::isAdmin($user)) {
            //this is an admin
            return Response::allow();
        } elseif (LemurPriv::isAuthor($user)  && $model->user_id === $user->id) {
            //this item belongs to the this user
            return Response::allow();
        } elseif (LemurPriv::isAuthor($user) && $hasMaster && $model->is_master) {
            //we allow the viewing of master items and this item is_master = true
            return Response::allow();
        } elseif (LemurPriv::isAuthor($user) && $hasParentMaster && $parentModel->is_master) {
            //we allow the viewing of master items and this item is_master = true
            return Response::allow();
        } else {
            return Response::deny($message);
        }
    }

    /**
     *
     * admins cant access these items only owners of the item or owners of the bot this item belongs to
     *
     * @param $user
     * @param $model
     * @param $bot
     * @param string $message
     * @return mixed
     */
    public function checkIfOwnerOrBotOwner($user, $model, $bot, $message = 'You cannot perform this action.')
    {


        if ($model->user_id === $user->id) {
            //this item belongs to the this user
            return Response::allow();
        } elseif ($bot->user_id === $user->id) {
            //this item belongs to the this user
            return Response::allow();
        } else {
            return Response::deny($message);
        }
    }
}
