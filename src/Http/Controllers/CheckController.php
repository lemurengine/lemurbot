<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\BotDataTable;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Http\Requests\CreateTalkRequest;
use LemurEngine\LemurBot\Models\Category;
use LemurEngine\LemurBot\Repositories\BotRepository;
use LemurEngine\LemurBot\Services\TalkService;
use Exception;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Auth\Access\Response as AuthResponse;

class CheckController extends AppBaseController
{



    /**
     * Display a listing of the Bot.
     *
     * @param BotDataTable $botDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index()
    {
        //if the user is not an admin do not allow access
        if (!LemurPriv::isAdmin(Auth::user())) {
            return AuthResponse::deny();
        }

        // get a nice list of word spelling groups
        return view('lemurbot::check.index')->with(
            [
                'link'=>$this->link,
                'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
                'resourceFolder'=>$this->resourceFolder,
            ]
        );

    }



}
