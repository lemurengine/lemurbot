<?php

use Illuminate\Support\Facades\Route;
use LemurEngine\LemurBot\Http\Middleware\TransformData;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//this can be overwritten in the main projects web.php
Route::group(['middleware' => ['web']], function() {
    Auth::routes(['verify' => true,'register' => false]);
});

Route::get('/home', 'LemurEngine\LemurBot\Http\Controllers\DashboardController@index')
    ->middleware(['web'])->name('home');

Route::get('/dashboard', 'LemurEngine\LemurBot\Http\Controllers\DashboardController@index')
    ->middleware(['web'])->name('dashboard');

Route::get('/botList', 'LemurEngine\LemurBot\Http\Controllers\BotController@list')
    ->middleware(['web']);

//Route::get('/profile', 'LemurEngine\LemurBot\Http\Controllers\UserController@profile')->middleware(['web']);
//Route::post('/profile', 'UserController@profileUpdate')->middleware(['web']);
Route::get('/tokens', 'LemurEngine\LemurBot\Http\Controllers\UserController@tokens')
    ->middleware(['web']);
Route::post('/tokens', 'LemurEngine\LemurBot\Http\Controllers\UserController@tokensUpdate')
    ->middleware(['web']);


Route::resource('users', 'LemurEngine\LemurBot\Http\Controllers\UserController')
    ->middleware(['web']);

Route::resource('languages', 'LemurEngine\LemurBot\Http\Controllers\LanguageController')
    ->middleware(['web']);

Route::resource('bots', 'LemurEngine\LemurBot\Http\Controllers\BotController')
    ->middleware(['web',TransformData::class]);

Route::resource('botCategoryGroups', 'LemurEngine\LemurBot\Http\Controllers\BotCategoryGroupController')
    ->middleware(['web',TransformData::class]);

Route::resource('clients', 'LemurEngine\LemurBot\Http\Controllers\ClientController')
    ->middleware(['web',TransformData::class]);

Route::resource('conversations', 'LemurEngine\LemurBot\Http\Controllers\ConversationController')
    ->middleware(['web',TransformData::class]);

Route::resource('maps', 'LemurEngine\LemurBot\Http\Controllers\MapController')
    ->middleware(['web']);

Route::resource('mapValues', 'LemurEngine\LemurBot\Http\Controllers\MapValueController')
    ->middleware(['web',TransformData::class]);

Route::resource('sections', 'LemurEngine\LemurBot\Http\Controllers\SectionController')
    ->middleware(['web']);

Route::resource('sets', 'LemurEngine\LemurBot\Http\Controllers\SetController')
    ->middleware(['web']);

Route::resource('setValues', 'LemurEngine\LemurBot\Http\Controllers\SetValueController')
    ->middleware(['web',TransformData::class]);

Route::GET('/test', 'LemurEngine\LemurBot\Http\Controllers\TestController@index')
    ->middleware(['web']);

Route::GET('/test/run', 'LemurEngine\LemurBot\Http\Controllers\TestController@run')
    ->middleware(['web']);

Route::resource('customDocs', 'LemurEngine\LemurBot\Http\Controllers\CustomDocController')
    ->middleware(['web',TransformData::class]);

/** ---------------------------------------------------------------
 *  Create category from an empty response
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/quickchat'], function () {

    Route::GET('/', 'LemurEngine\LemurBot\Http\Controllers\BotController@quickChat')
        ->middleware(['web']);

});


/** ---------------------------------------------------------------
 *  Create category from an empty response
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/category/fromEmptyResponse'], function () {

    Route::bind('emptyResponseSlug', function ($emptyResponseSlug) {
        try {
            $emptyResponse = LemurEngine\LemurBot\Models\EmptyResponse::where('slug', $emptyResponseSlug)->firstOrFail();
            return $emptyResponse->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{emptyResponseSlug}', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@createFromEmptyResponse')
        ->middleware(['web',TransformData::class]);
});


/** ---------------------------------------------------------------
 *  Create category from a turn
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/category/fromTurn'], function () {

    Route::bind('turnSlug', function ($turnSlug) {
        try {
            $turn = LemurEngine\LemurBot\Models\Turn::where('slug', $turnSlug)->firstOrFail();
            return $turn->id;
        } catch (Exception $e) {
            abort(404);
        }
    });


    Route::GET('/{turnSlug}', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@createFromTurn')
        ->middleware(['web',TransformData::class]);
});

Route::group(['prefix' => '/category/fromClientCategory'], function () {

    Route::bind('clientCategorySlug', function ($clientCategorySlug) {

        try {
            $clientCategory = LemurEngine\LemurBot\Models\ClientCategory::where('slug', $clientCategorySlug)->firstOrFail();
            return $clientCategory->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{clientCategorySlug}', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@createFromClientCategory')
        ->middleware(['web',TransformData::class]);
});

Route::group(['prefix' => '/category/fromMachineLearntCategory'], function () {

    Route::bind('machineLearntCategorySlug', function ($machineLearntCategorySlug) {

        try {
            $machineLearntCategory = LemurEngine\LemurBot\Models\MachineLearntCategory::where('slug', $machineLearntCategorySlug)->firstOrFail();
            return $machineLearntCategory->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{machineLearntCategorySlug}', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@createFromMachineLearntCategory')
        ->middleware(['web',TransformData::class]);
});


Route::group(['prefix' => '/category/fromCopy'], function () {

    Route::bind('categorySlug', function ($categorySlug) {

        try {
            $category = LemurEngine\LemurBot\Models\Category::where('slug', $categorySlug)->firstOrFail();
            return $category->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/{categorySlug}', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@createFromCopy')
        ->middleware(['web',TransformData::class]);
});

Route::resource('categories', 'LemurEngine\LemurBot\Http\Controllers\CategoryController')
    ->middleware(['web',TransformData::class]);

Route::resource('normalizations', 'LemurEngine\LemurBot\Http\Controllers\NormalizationController')
    ->middleware(['web',TransformData::class]);

Route::resource('wordSpellings', 'LemurEngine\LemurBot\Http\Controllers\WordSpellingController')
    ->middleware(['web',TransformData::class]);

Route::resource('wordTransformations', 'LemurEngine\LemurBot\Http\Controllers\WordTransformationController')
    ->middleware(['web',TransformData::class]);

Route::resource('conversationProperties', 'LemurEngine\LemurBot\Http\Controllers\ConversationPropertyController')
    ->middleware(['web',TransformData::class]);

Route::resource('conversationSources', 'LemurEngine\LemurBot\Http\Controllers\ConversationSourceController')
    ->middleware(['web',TransformData::class]);

Route::resource('clientCategories', 'LemurEngine\LemurBot\Http\Controllers\ClientCategoryController')
    ->middleware(['web',TransformData::class]);

Route::resource('machineLearntCategories', 'LemurEngine\LemurBot\Http\Controllers\MachineLearntCategoryController')
    ->middleware(['web',TransformData::class]);

Route::resource('emptyResponses', 'LemurEngine\LemurBot\Http\Controllers\EmptyResponseController')
    ->middleware(['web',TransformData::class]);

Route::resource('botProperties', 'LemurEngine\LemurBot\Http\Controllers\BotPropertyController')
    ->middleware(['web',TransformData::class]);

Route::resource('botWordSpellingGroups', 'LemurEngine\LemurBot\Http\Controllers\BotWordSpellingGroupController')
    ->middleware(['web',TransformData::class]);

Route::resource('categoryGroups', 'LemurEngine\LemurBot\Http\Controllers\CategoryGroupController')
    ->middleware(['web',TransformData::class]);

Route::resource('turns', 'LemurEngine\LemurBot\Http\Controllers\TurnController')
    ->middleware(['web',TransformData::class]);

Route::resource('wordSpellingGroups', 'LemurEngine\LemurBot\Http\Controllers\WordSpellingGroupController')
    ->middleware(['web',TransformData::class]);

Route::Get('botList/create', 'LemurEngine\LemurBot\Http\Controllers\BotController@create')
    ->middleware(['web',TransformData::class]);

Route::resource('wildcards', 'LemurEngine\LemurBot\Http\Controllers\WildcardController')
    ->middleware(['web',TransformData::class]);

Route::resource('botKeys', 'LemurEngine\LemurBot\Http\Controllers\BotKeyController')
    ->middleware(['web',TransformData::class]);

Route::resource('botAllowedSites', 'LemurEngine\LemurBot\Http\Controllers\BotAllowedSiteController')
    ->middleware(['web',TransformData::class]);

Route::delete('botRatings/reset', 'LemurEngine\LemurBot\Http\Controllers\BotRatingController@reset')
    ->middleware(['web',TransformData::class]);

Route::resource('botRatings', 'LemurEngine\LemurBot\Http\Controllers\BotRatingController')
    ->middleware(['web',TransformData::class]);


/** ---------------------------------------------------------------
 *  SUPER ADMIN BOT TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/bots'], function () {
    Route::bind('saBotSlug', function ($slug) {
        try {
            $bot = LemurEngine\LemurBot\Models\Bot::where('slug', $slug)->withTrashed()->firstOrFail();
            return $bot;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saBotSlug}', 'LemurEngine\LemurBot\Http\Controllers\BotController@slugUpdate')
        ->middleware(['web']);
    Route::PATCH('/restore/{saBotSlug}', 'LemurEngine\LemurBot\Http\Controllers\BotController@restore')
        ->middleware(['web']);
    Route::DELETE('/{saBotSlug}', 'LemurEngine\LemurBot\Http\Controllers\BotController@forceDestroy')
        ->middleware(['web']);
});


/** ---------------------------------------------------------------
 *  SUPER ADMIN CUSTOM DOC TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/customDocs'], function () {
    Route::bind('saCustomDocSlug', function ($saCustomDocSlug) {
        try {
            $customDoc = LemurEngine\LemurBot\Models\CustomDoc::where('slug', $saCustomDocSlug)->withTrashed()->firstOrFail();
            return $customDoc;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saCustomDocSlug}', 'LemurEngine\LemurBot\Http\Controllers\CustomDocController@slugUpdate')
        ->middleware(['web']);
    Route::PATCH('/restore/{saCustomDocSlug}', 'LemurEngine\LemurBot\Http\Controllers\CustomDocController@restore')
        ->middleware(['web']);
    Route::DELETE('/{saCustomDocSlug}', 'LemurEngine\LemurBot\Http\Controllers\CustomDocController@forceDestroy')
        ->middleware(['web']);
});

/** ---------------------------------------------------------------
 *  SUPER ADMIN BOT USER TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/users'], function () {
    Route::bind('saUserSlug', function ($slug) {
        try {
            $user = LemurEngine\LemurBot\Models\User::where('slug', $slug)->withTrashed()->firstOrFail();
            return $user;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saUserSlug}', 'LemurEngine\LemurBot\Http\Controllers\UserController@slugUpdate')
        ->middleware(['web']);
    Route::PATCH('/restore/{saUserSlug}', 'LemurEngine\LemurBot\Http\Controllers\UserController@restore')
        ->middleware(['web']);
    Route::DELETE('/{saUserSlug}', 'LemurEngine\LemurBot\Http\Controllers\UserController@forceDestroy')
        ->middleware(['web']);
});


/** ---------------------------------------------------------------
 *  SUPER ADMIN CLIENT TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/clients'], function () {
    Route::bind('saClientSlug', function ($slug) {
        try {
            $client = LemurEngine\LemurBot\Models\Client::where('slug', $slug)->firstOrFail();
            return $client;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saClientSlug}', 'LemurEngine\LemurBot\Http\Controllers\ClientController@slugUpdate')
        ->middleware(['web']);
});

/** ---------------------------------------------------------------
 *  SUPER ADMIN LANGUAGE TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/languages'], function () {
    Route::bind('saLanguageSlug', function ($slug) {
        try {
            $language = LemurEngine\LemurBot\Models\Language::where('slug', $slug)->firstOrFail();
            return $language;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saLanguageSlug}', 'LemurEngine\LemurBot\Http\Controllers\LanguageController@slugUpdate')
        ->middleware(['web']);
});

/** ---------------------------------------------------------------
 *  SUPER ADMIN CONVERSATION TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/conversations'], function () {
    Route::bind('saConversationSlug', function ($slug) {
        try {
            $conversation = LemurEngine\LemurBot\Models\Conversation::where('slug', $slug)->firstOrFail();
            return $conversation;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::PATCH('/slug/{saConversationSlug}', 'LemurEngine\LemurBot\Http\Controllers\ConversationController@slugUpdate')
        ->middleware(['web']);
});


/** ---------------------------------------------------------------
 *  BOT EDIT ROUTER
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/bot'], function () {
    Route::bind('botSlug', function ($slug) {
        try {
            $bot = LemurEngine\LemurBot\Models\Bot::where('slug', $slug)->firstOrFail();
            return $bot->id;
        } catch (Exception $e) {
            abort(404);
        }
    });

    Route::GET('/properties/{botSlug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@botProperties')
    ->middleware(['web']);
    Route::GET('/properties/{botSlug}/download', 'LemurEngine\LemurBot\Http\Controllers\BotPropertyController@botPropertiesDownload')
    ->middleware(['web']);
    Route::GET('/keys/{botSlug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@botKeys')
    ->middleware(['web']);
    Route::GET('/sites/{botSlug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@botSites')
        ->middleware(['web']);

    Route::GET('/categories/{botSlug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@botCategoryGroups')
    ->middleware(['web']);
    Route::GET('/logs/{botSlug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@conversations')
    ->middleware(['web']);
    Route::GET('/logs/{botSlug}/{conversationSlug}/download', 'LemurEngine\LemurBot\Http\Controllers\ConversationController@downloadCsv')
        ->middleware(['web']);
    Route::GET('/logs/{botSlug}/{conversationSlug}', 'LemurEngine\LemurBot\Http\Controllers\BotController@conversations')
    ->middleware(['web']);
    Route::GET('/plugins/{botSlug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@botPlugins')
    ->middleware(['web']);
    Route::GET('/widget/{botSlug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@widgets')
    ->middleware(['web']);
    Route::GET('/stats/{botSlug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@stats')
    ->middleware(['web']);
    Route::POST('/stats/{botSlug}/download', 'LemurEngine\LemurBot\Http\Controllers\BotController@stat_download')
        ->middleware(['web']);
    Route::GET('/{botSlug}/chat', 'LemurEngine\LemurBot\Http\Controllers\BotController@chatForm')
    ->middleware(['web']);
    Route::POST('/{botSlug}/chat', 'LemurEngine\LemurBot\Http\Controllers\BotController@chat')
    ->middleware(['web']);
});


/** ---------------------------------------------------------------
 *  UPLOAD ROUTER
 ** -------------------------------------------------------------- */
Route::GET('categoriesUpload', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@uploadForm')
    ->middleware(['web']);
Route::POST('categoriesUpload', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@upload')
    ->middleware(['web']);

Route::GET('mapValuesUpload', 'LemurEngine\LemurBot\Http\Controllers\MapValueController@uploadForm')
    ->middleware(['web']);
Route::POST('mapValuesUpload', 'LemurEngine\LemurBot\Http\Controllers\MapValueController@upload')
    ->middleware(['web']);

Route::GET('setValuesUpload', 'LemurEngine\LemurBot\Http\Controllers\SetValueController@uploadForm')
    ->middleware(['web']);
Route::POST('setValuesUpload', 'LemurEngine\LemurBot\Http\Controllers\SetValueController@upload')
    ->middleware(['web']);

Route::GET('wordSpellingsUpload', 'LemurEngine\LemurBot\Http\Controllers\WordSpellingController@uploadForm')
    ->middleware(['web']);
Route::POST('wordSpellingsUpload', 'LemurEngine\LemurBot\Http\Controllers\WordSpellingController@upload')
    ->middleware(['web']);

Route::GET('wordTransformationsUpload', 'LemurEngine\LemurBot\Http\Controllers\WordTransformationController@uploadForm')
    ->middleware(['web']);
Route::POST('wordTransformationsUpload', 'LemurEngine\LemurBot\Http\Controllers\WordTransformationController@upload')
    ->middleware(['web']);

Route::GET('botPropertiesUpload', 'LemurEngine\LemurBot\Http\Controllers\BotPropertyController@uploadForm')
    ->middleware(['web']);
Route::POST('botPropertiesUpload', 'LemurEngine\LemurBot\Http\Controllers\BotPropertyController@upload')
    ->middleware(['web']);


Route::GET('/categories/{categoryGroupSlug}/download/csv', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@downloadCsv')
    ->middleware(['web']);
Route::GET('/categories/{categoryGroupSlug}/download/aiml', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@downloadAiml')
    ->middleware(['web']);
Route::GET('/wordSpellings/{wordSpellingGroupSlug}/download', 'LemurEngine\LemurBot\Http\Controllers\WordSpellingController@download')
    ->middleware(['web']);
