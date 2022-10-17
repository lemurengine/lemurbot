<?php

namespace LemurEngine\LemurBot\Policies;

use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\Conversation;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\Response;

class ConversationPolicy extends MasterPolicy
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
     * @param Conversation $conversation
     * @return mixed
     */
    public function view(User $user, Conversation $conversation)
    {
        //the $conversation belongs to a bot and that bot belongs to a user
        //and if this bot is a master then anyone can view
        return $this->checkIfAdminOrOwner($user, $this->getBot($conversation), true);
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
     * @param Conversation $conversation
     * @return mixed
     */
    public function update(User $user, Conversation $conversation)
    {
        //the $conversation belongs to a bot and that bot belongs to a user
        return $this->checkIfAdminOrOwner($user, $this->getBot($conversation));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Conversation $conversation
     * @return mixed
     */
    public function delete(User $user, Conversation $conversation)
    {
        //the $conversation belongs to a bot and that bot belongs to a user
        return $this->checkIfAdminOrOwner($user, $this->getBot($conversation));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Conversation $conversation
     * @return mixed
     */
    public function restore(User $user, Conversation $conversation)
    {
        //the $conversation belongs to a bot and that bot belongs to a user
        return $this->checkIfAdminOrOwner($user, $this->getBot($conversation));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Conversation $conversation
     * @return mixed
     */
    public function forceDelete(User $user, Conversation $conversation)
    {
        //only admins can do this
        return LemurPriv::isAdmin($user)
            ? Response::allow()
            : Response::deny('You cannot perform this action.');
    }


    /**
     * @param $model
     * @return mixed
     */
    public function getBot($model)
    {
        return Bot::find($model->bot_id);
    }
}
