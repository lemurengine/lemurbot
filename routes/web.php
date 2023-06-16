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
Route::group(['middleware' => ['web']], function () {
    Auth::routes(['verify' => true, 'register' => false]);
});

Route::get('/home', 'LemurEngine\LemurBot\Http\Controllers\DashboardController@index')
    ->middleware(['web'])->name('home')->name('get.home');

Route::get('/dashboard', 'LemurEngine\LemurBot\Http\Controllers\DashboardController@index')
    ->middleware(['web'])->name('dashboard')->name('get.dashboard');

Route::get('/botList', 'LemurEngine\LemurBot\Http\Controllers\BotController@list')
    ->middleware(['web'])->name('get.botlist');

//Route::get('/profile', 'LemurEngine\LemurBot\Http\Controllers\UserController@profile')->middleware(['web']);
//Route::post('/profile', 'UserController@profileUpdate')->middleware(['web']);
Route::get('/tokens', 'LemurEngine\LemurBot\Http\Controllers\UserController@tokens')
    ->middleware(['web'])
    ->name('tokens.get');
Route::post('/tokens', 'LemurEngine\LemurBot\Http\Controllers\UserController@tokensUpdate')
    ->middleware(['web'])
    ->name('tokens.post');

Route::resource('users', 'LemurEngine\LemurBot\Http\Controllers\UserController')
    ->middleware(['web']);

Route::resource('languages', 'LemurEngine\LemurBot\Http\Controllers\LanguageController')
    ->middleware(['web']);

Route::resource('botCategoryGroups', 'LemurEngine\LemurBot\Http\Controllers\BotCategoryGroupController')
    ->middleware(['web', TransformData::class]);

Route::resource('clients', 'LemurEngine\LemurBot\Http\Controllers\ClientController')
    ->middleware(['web', TransformData::class]);

Route::resource('conversations', 'LemurEngine\LemurBot\Http\Controllers\ConversationController')
    ->middleware(['web', TransformData::class]);

Route::resource('maps', 'LemurEngine\LemurBot\Http\Controllers\MapController')
    ->middleware(['web']);

Route::resource('mapValues', 'LemurEngine\LemurBot\Http\Controllers\MapValueController')
    ->middleware(['web', TransformData::class]);

Route::resource('sections', 'LemurEngine\LemurBot\Http\Controllers\SectionController')
    ->middleware(['web']);

Route::resource('sets', 'LemurEngine\LemurBot\Http\Controllers\SetController')
    ->middleware(['web']);

Route::resource('setValues', 'LemurEngine\LemurBot\Http\Controllers\SetValueController')
    ->middleware(['web', TransformData::class]);

Route::GET('/test', 'LemurEngine\LemurBot\Http\Controllers\TestController@index')
    ->middleware(['web'])
    ->name('test.get');

Route::GET('/test/run', 'LemurEngine\LemurBot\Http\Controllers\TestController@run')
    ->middleware(['web'])
    ->name('test.run.get');

Route::resource('customDocs', 'LemurEngine\LemurBot\Http\Controllers\CustomDocController')
    ->middleware(['web', TransformData::class]);

/** ---------------------------------------------------------------
 *  Quick chat with a bot
 ** -------------------------------------------------------------- */
Route::GET('/quickchat', 'LemurEngine\LemurBot\Http\Controllers\BotController@quickChat')
    ->middleware(['web'])
    ->name('quickchat.get');

Route::group(['prefix' => '/category'], function () {
    // Create category from an empty response
    Route::GET('/fromEmptyResponse/{emptyResponse:slug}', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@createFromEmptyResponse')
        ->middleware(['web', TransformData::class])
        ->name('category.fromEmptyResponse.get');
    // Create category from a turn
    Route::GET('/fromTurn/{turn:slug}', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@createFromTurn')
        ->middleware(['web', TransformData::class])
        ->name('category.fromTurn.get');
    // Create category from a client category
    Route::GET('/fromClientCategory/{clientCategory:slug}', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@createFromClientCategory')
        ->middleware(['web', TransformData::class])
        ->name('category.fromClientCategory.get');
    // Create category from a client category
    Route::GET('/fromMachineLearntCategory/{machineLearntCategory:slug}', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@createFromMachineLearntCategory')
        ->middleware(['web', TransformData::class])
        ->name('category.fromMachineLearntCategory.get');
    // new category from a existing category
    Route::GET('/fromCopy/{category:slug}', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@createFromCopy')
        ->middleware(['web', TransformData::class])
        ->name('category.fromCopy.get');
});

Route::resource('categories', 'LemurEngine\LemurBot\Http\Controllers\CategoryController')
    ->middleware(['web', TransformData::class]);

Route::resource('normalizations', 'LemurEngine\LemurBot\Http\Controllers\NormalizationController')
    ->middleware(['web', TransformData::class]);

Route::resource('wordSpellings', 'LemurEngine\LemurBot\Http\Controllers\WordSpellingController')
    ->middleware(['web', TransformData::class]);

Route::resource('wordTransformations', 'LemurEngine\LemurBot\Http\Controllers\WordTransformationController')
    ->middleware(['web', TransformData::class]);

Route::resource('conversationProperties', 'LemurEngine\LemurBot\Http\Controllers\ConversationPropertyController')
    ->middleware(['web', TransformData::class]);

Route::resource('conversationSources', 'LemurEngine\LemurBot\Http\Controllers\ConversationSourceController')
    ->middleware(['web', TransformData::class]);

Route::resource('clientCategories', 'LemurEngine\LemurBot\Http\Controllers\ClientCategoryController')
    ->middleware(['web', TransformData::class]);

Route::resource('machineLearntCategories', 'LemurEngine\LemurBot\Http\Controllers\MachineLearntCategoryController')
    ->middleware(['web', TransformData::class]);

Route::resource('emptyResponses', 'LemurEngine\LemurBot\Http\Controllers\EmptyResponseController')
    ->middleware(['web', TransformData::class]);

Route::resource('botProperties', 'LemurEngine\LemurBot\Http\Controllers\BotPropertyController')
    ->middleware(['web', TransformData::class]);

Route::resource('botWordSpellingGroups', 'LemurEngine\LemurBot\Http\Controllers\BotWordSpellingGroupController')
    ->middleware(['web', TransformData::class]);

Route::resource('categoryGroups', 'LemurEngine\LemurBot\Http\Controllers\CategoryGroupController')
    ->middleware(['web', TransformData::class]);

Route::resource('turns', 'LemurEngine\LemurBot\Http\Controllers\TurnController')
    ->middleware(['web', TransformData::class]);

Route::resource('wordSpellingGroups', 'LemurEngine\LemurBot\Http\Controllers\WordSpellingGroupController')
    ->middleware(['web', TransformData::class]);

Route::Get('botList/create', 'LemurEngine\LemurBot\Http\Controllers\BotController@create')
    ->middleware(['web', TransformData::class]);

Route::resource('wildcards', 'LemurEngine\LemurBot\Http\Controllers\WildcardController')
    ->middleware(['web', TransformData::class]);

Route::resource('botKeys', 'LemurEngine\LemurBot\Http\Controllers\BotKeyController')
    ->middleware(['web', TransformData::class]);

Route::resource('botAllowedSites', 'LemurEngine\LemurBot\Http\Controllers\BotAllowedSiteController')
    ->middleware(['web', TransformData::class]);

Route::delete('botRatings/reset', 'LemurEngine\LemurBot\Http\Controllers\BotRatingController@reset')
    ->middleware(['web', TransformData::class]);

Route::resource('botRatings', 'LemurEngine\LemurBot\Http\Controllers\BotRatingController')
    ->middleware(['web', TransformData::class]);


/** ---------------------------------------------------------------
 *  SUPER ADMIN BOT TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/bots'], function () {

    Route::PATCH('/slug/{bot:slug}', 'LemurEngine\LemurBot\Http\Controllers\BotController@slugUpdate')
        ->withTrashed()
        ->middleware(['web'])
        ->name('saBotSlug.update');
    Route::PATCH('/restore/{bot:slug}', 'LemurEngine\LemurBot\Http\Controllers\BotController@restore')
        ->withTrashed()
        ->middleware(['web'])
        ->name('saBot.restore');
    Route::DELETE('/{bot:slug}', 'LemurEngine\LemurBot\Http\Controllers\BotController@forceDestroy')
        ->withTrashed()
        ->middleware(['web'])
        ->name('saBot.destroy');
});


/** ---------------------------------------------------------------
 *  SUPER ADMIN CUSTOM DOC TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/customDocs'], function () {

    Route::PATCH('/slug/{customDoc:slug}', 'LemurEngine\LemurBot\Http\Controllers\CustomDocController@slugUpdate')
        ->withTrashed()
        ->middleware(['web'])
        ->name('saCustomDocSlug.update');
    Route::PATCH('/restore/{customDoc:slug}', 'LemurEngine\LemurBot\Http\Controllers\CustomDocController@restore')
        ->withTrashed()
        ->middleware(['web'])
        ->name('saCustomDoc.restore');
    Route::DELETE('/{customDoc:slug}', 'LemurEngine\LemurBot\Http\Controllers\CustomDocController@forceDestroy')
        ->withTrashed()
        ->middleware(['web'])
        ->name('saCustom.destroy');
});

/** ---------------------------------------------------------------
 *  SUPER ADMIN BOT USER TASKS
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/sa/users'], function () {

    Route::PATCH('/slug/{user:slug}', 'LemurEngine\LemurBot\Http\Controllers\UserController@slugUpdate')
        ->withTrashed()
        ->middleware(['web'])
        ->name('saUserSlug.update');
    Route::PATCH('/restore/{user:slug}', 'LemurEngine\LemurBot\Http\Controllers\UserController@restore')
        ->withTrashed()
        ->middleware(['web'])
        ->name('saUser.restore');
    Route::DELETE('/{user:slug}', 'LemurEngine\LemurBot\Http\Controllers\UserController@forceDestroy')
        ->withTrashed()
        ->middleware(['web'])
        ->name('saUser.destroy');
});


/** ---------------------------------------------------------------
 *  SUPER ADMIN CLIENT TASKS
 ** -------------------------------------------------------------- */
Route::PATCH('/sa/clients/slug/client:slug}', 'LemurEngine\LemurBot\Http\Controllers\ClientController@slugUpdate')
    ->middleware(['web'])
    ->name('saClientSlug.update');
/** ---------------------------------------------------------------
 *  SUPER ADMIN LANGUAGE TASKS
 ** -------------------------------------------------------------- */
Route::PATCH('/sa/languages/slug/{language:slug}', 'LemurEngine\LemurBot\Http\Controllers\LanguageController@slugUpdate')
    ->middleware(['web'])
    ->name('saLanguageSlug.update');

/** ---------------------------------------------------------------
 *  SUPER ADMIN CONVERSATION TASKS
 ** -------------------------------------------------------------- */
Route::PATCH('/sa/conversations/slug/{conversation:slug}', 'LemurEngine\LemurBot\Http\Controllers\ConversationController@slugUpdate')
    ->middleware(['web'])
    ->name('saConversationSlug.update');

/** ---------------------------------------------------------------
 *  BOT EDIT ROUTER
 ** -------------------------------------------------------------- */
Route::group(['prefix' => '/bot'], function () {

    Route::GET('/properties/{bot:slug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@botProperties')
        ->middleware(['web'])
        ->name('botPropertiesList.get');
    Route::GET('/properties/{bot:slug}/download', 'LemurEngine\LemurBot\Http\Controllers\BotPropertyController@botPropertiesDownload')
        ->middleware(['web'])
        ->name('botPropertiesDownload.get');
    Route::GET('/keys/{bot:slug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@botKeys')
        ->middleware(['web'])
        ->name('botKeysList.get');
    Route::GET('/sites/{bot:slug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@botSites')
        ->middleware(['web'])
        ->name('botSitesList.get');

    Route::GET('/categories/{bot:slug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@botCategoryGroups')
        ->middleware(['web'])
        ->name('botCategoriesListAll.get');
    Route::GET('/logs/{bot:slug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@conversations')
        ->middleware(['web'])
        ->name('botLogListAll.get');
    Route::GET('/logs/{bot:slug}/{conversationSlug}/download', 'LemurEngine\LemurBot\Http\Controllers\ConversationController@downloadCsv')
        ->middleware(['web'])
        ->name('botLogDownloadCsv.get');
    Route::GET('/logs/{bot:slug}/{conversationSlug}', 'LemurEngine\LemurBot\Http\Controllers\BotController@conversations')
        ->middleware(['web'])
        ->name('botLogListConversation.get');
    Route::GET('/plugins/{bot:slug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@botPlugins')
        ->middleware(['web'])
        ->name('botPluginList.get');
    Route::GET('/widget/{bot:slug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@widgets')
        ->middleware(['web'])
        ->name('botWidgetList.get');
    Route::GET('/stats/{bot:slug}/list', 'LemurEngine\LemurBot\Http\Controllers\BotController@stats')
        ->middleware(['web'])
        ->name('botStatsDownload.get');
    Route::POST('/stats/{bot:slug}/download', 'LemurEngine\LemurBot\Http\Controllers\BotController@stat_download')
        ->middleware(['web'])
        ->name('botStatsDownload.post');
    Route::GET('/{bot:slug}/chat', 'LemurEngine\LemurBot\Http\Controllers\BotController@chatForm')
        ->middleware(['web'])
        ->name('botChat.get');
    Route::POST('/{bot:slug}/chat', 'LemurEngine\LemurBot\Http\Controllers\BotController@chat')
        ->middleware(['web'])
        ->name('botChat.post');
    Route::GET('/{bot:slug}/popup', 'LemurEngine\LemurBot\Http\Controllers\BotController@popupChatForm')
        ->middleware(['web'])
        ->name('botPopupChat.get');
    Route::POST('/{bot:slug}/popup', 'LemurEngine\LemurBot\Http\Controllers\BotController@popupChat')
        ->middleware(['web'])
        ->name('botPopupChat.post');
});


/** ---------------------------------------------------------------
 *  UPLOAD ROUTER
 ** -------------------------------------------------------------- */
Route::GET('categoriesUpload', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@uploadForm')
    ->middleware(['web'])
    ->name('categoriesUpload.get');
Route::POST('categoriesUpload', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@upload')
    ->middleware(['web'])
    ->name('categoriesUpload.post');

Route::GET('mapValuesUpload', 'LemurEngine\LemurBot\Http\Controllers\MapValueController@uploadForm')
    ->middleware(['web'])
    ->name('mapValuesUpload.get');
Route::POST('mapValuesUpload', 'LemurEngine\LemurBot\Http\Controllers\MapValueController@upload')
    ->middleware(['web'])
    ->name('mapValuesUpload.post');

Route::GET('setValuesUpload', 'LemurEngine\LemurBot\Http\Controllers\SetValueController@uploadForm')
    ->middleware(['web'])
    ->name('setValuesUpload.get');
Route::POST('setValuesUpload', 'LemurEngine\LemurBot\Http\Controllers\SetValueController@upload')
    ->middleware(['web'])
    ->name('setValuesUpload.post');

Route::GET('wordSpellingsUpload', 'LemurEngine\LemurBot\Http\Controllers\WordSpellingController@uploadForm')
    ->middleware(['web'])
    ->name('wordSpellingsUpload.get');
Route::POST('wordSpellingsUpload', 'LemurEngine\LemurBot\Http\Controllers\WordSpellingController@upload')
    ->middleware(['web'])
    ->name('wordSpellingsUpload.post');

Route::GET('wordTransformationsUpload', 'LemurEngine\LemurBot\Http\Controllers\WordTransformationController@uploadForm')
    ->middleware(['web'])
    ->name('wordTransformationsUpload.get');
Route::POST('wordTransformationsUpload', 'LemurEngine\LemurBot\Http\Controllers\WordTransformationController@upload')
    ->middleware(['web'])
    ->name('wordTransformationsUpload.post');

Route::GET('botPropertiesUpload', 'LemurEngine\LemurBot\Http\Controllers\BotPropertyController@uploadForm')
    ->middleware(['web'])
    ->name('botPropertiesUpload.get');
Route::POST('botPropertiesUpload', 'LemurEngine\LemurBot\Http\Controllers\BotPropertyController@upload')
    ->middleware(['web'])
    ->name('botPropertiesUpload.post');


Route::GET('/categories/{categoryGroupSlug}/download/csv', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@downloadCsv')
    ->middleware(['web'])
    ->name('categoriesDownloadCsv.get');
Route::GET('/categories/{categoryGroupSlug}/download/aiml', 'LemurEngine\LemurBot\Http\Controllers\CategoryController@downloadAiml')
    ->middleware(['web'])
    ->name('categoriesDownloadAiml.get');
Route::GET('/wordSpellings/{wordSpellingGroupSlug}/download', 'LemurEngine\LemurBot\Http\Controllers\WordSpellingController@download')
    ->middleware(['web'])
    ->name('wordSpellingsDownload.get');

Route::resource('plugins', 'LemurEngine\LemurBot\Http\Controllers\PluginController')
    ->middleware(['web', TransformData::class]);

Route::resource('botPlugins', 'LemurEngine\LemurBot\Http\Controllers\BotPluginController')->middleware(['web', TransformData::class]);

Route::resource('bots', 'LemurEngine\LemurBot\Http\Controllers\BotController')
    ->middleware(['web', TransformData::class]);
