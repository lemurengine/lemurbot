<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Bot;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {

        $authorBots = Bot::where('user_id', Auth::user()->id)->orderBy('name')->get();

        $publicBots = Bot::where('user_id', '!=', Auth::user()->id)->where('is_public', 1)->orderBy('name')->get();

        return view('lemurbot::dashboard')->with(
            [
            'authorBots'=>$authorBots, 'publicBots'=>$publicBots]
        );
    }
}
