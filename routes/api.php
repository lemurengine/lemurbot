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

Route::post('/api/talk/bot', 'LemurEngine\LemurBot\Http\Controllers\API\TalkAPIController@store')->name('post.bot.talk');
Route::post('/api/talk/meta', 'LemurEngine\LemurBot\Http\Controllers\API\TalkAPIController@old_meta')->name('post.bot.meta');

/** ---------------------------------------------------------------
 *  Deprecated route to get the bot meta
 ** -------------------------------------------------------------- */
Route::GET('/api/meta/{bot:slug}', 'LemurEngine\LemurBot\Http\Controllers\API\TalkAPIController@meta')->name('get.bot.talk');
