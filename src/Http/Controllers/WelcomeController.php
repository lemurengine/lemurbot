<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Illuminate\Http\Response;

class WelcomeController extends Controller
{

    /**
     * Show the application homepage.
     *
     * @return Response
     */
    public function index()
    {

        return view('lemurbot::welcome');
    }
}
