<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/api/talk/bot', 'LemurEngine\LemurBot\Http\Controllers\API\TalkAPIController@store');
Route::post('/api/talk/meta', 'LemurEngine\LemurBot\Http\Controllers\API\TalkAPIController@old_meta');



/** ---------------------------------------------------------------
 *  Create category from an empty response
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/api/meta'], function () {

    Route::bind('botMetaSlug', function ($botMetaSlug) {
        try {
            $bot = LemurEngine\LemurBot\Models\Bot::where('slug', $botMetaSlug)->firstOrFail();
            return $bot->slug;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{botMetaSlug}', 'LemurEngine\LemurBot\Http\Controllers\API\TalkAPIController@meta');
});
