<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * Update the authenticated user's API token.
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => $token,
        ])->save();

        return ['token' => $token];
    }
}
