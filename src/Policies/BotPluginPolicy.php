<?php

namespace LemurEngine\LemurBot\Policies;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\BotPlugin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\Response;

class BotPluginPolicy extends MasterPolicy
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
     * @param BotPlugin $botPlugin
     * @return mixed
     */
    public function view(User $user, BotPlugin $botPlugin)
    {
        $bot = Bot::find($botPlugin->bot_id);
        return $this->checkIfOwnerOrBotOwner($user, $botPlugin, $bot);
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
     * @param BotPlugin $botPlugin
     * @return mixed
     */
    public function update(User $user, BotPlugin $botPlugin)
    {
        $bot = Bot::find($botPlugin->bot_id);
        return $this->checkIfOwnerOrBotOwner($user, $botPlugin, $bot);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param BotPlugin $botPlugin
     * @return mixed
     */
    public function delete(User $user, BotPlugin $botPlugin)
    {
        $bot = Bot::find($botPlugin->bot_id);
        return $this->checkIfOwnerOrBotOwner($user, $botPlugin, $bot);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param BotPlugin $botPlugin
     * @return mixed
     */
    public function restore(User $user, BotPlugin $botPlugin)
    {
        $bot = Bot::find($botPlugin->bot_id);
        return $this->checkIfOwnerOrBotOwner($user, $botPlugin, $bot);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param BotPlugin $botPlugin
     * @return mixed
     */
    public function forceDelete(User $user, BotPlugin $botPlugin)
    {
        //only admins can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }
}