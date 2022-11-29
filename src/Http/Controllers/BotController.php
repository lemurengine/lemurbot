<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use LemurEngine\LemurBot\DataTables\BotDataTable;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Http\Requests\CreateBotRequest;
use LemurEngine\LemurBot\Http\Requests\CreateTalkRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateBotRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateBotSlugRequest;
use LemurEngine\LemurBot\Models\BotAllowedSite;
use LemurEngine\LemurBot\Models\BotCategoryGroup;
use LemurEngine\LemurBot\Models\BotKey;
use LemurEngine\LemurBot\Models\BotPlugin;
use LemurEngine\LemurBot\Models\BotProperty;
use LemurEngine\LemurBot\Models\BotWordSpellingGroup;
use LemurEngine\LemurBot\Models\Client;
use LemurEngine\LemurBot\Models\Conversation;
use LemurEngine\LemurBot\Models\ConversationProperty;
use LemurEngine\LemurBot\Models\ConversationSource;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\Plugin;
use LemurEngine\LemurBot\Models\Section;
use LemurEngine\LemurBot\Repositories\BotPropertyRepository;
use LemurEngine\LemurBot\Repositories\BotRepository;
use LemurEngine\LemurBot\Services\BotStatsService;
use LemurEngine\LemurBot\Services\TalkService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Bot;

class BotController extends AppBaseController
{
    private BotRepository $botRepository;
    private BotPropertyRepository $botPropertyRepository;

    //to help with data testing and form settings
    public string $link = 'bots';
    public string $htmlTag = 'bots';
    public string $title = 'Bots';
    public string $resourceFolder = 'lemurbot::bots';

    public function __construct(BotRepository $botRepo, BotPropertyRepository $botPropertyRepo)
    {
        $this->middleware('auth');
        $this->botRepository = $botRepo;
        $this->botPropertyRepository = $botPropertyRepo;
    }

    /**
     * Display a listing of the Bot.
     *
     * @param BotDataTable $botDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(BotDataTable $botDataTable)
    {

        $this->authorize('viewAny', Bot::class);
        $botDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }


    /**
     * Display a listing of the Bot.
     *
     * @param BotDataTable $botDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function list(BotDataTable $botDataTable)
    {
        $this->authorize('viewAny', Bot::class);
        $botDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Bot.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Bot::class);

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::bots.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList,
            'readonly'=>false]
        );
    }

    /**
     * Store a newly created Bot in storage.
     *
     * @param CreateBotRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateBotRequest $request)
    {
        $this->authorize('create', Bot::class);

        $input = $request->all();

        //start the transaction
        DB::beginTransaction();

        try {

            $bot = $this->botRepository->create($input);
            $bot = $this->uploadImage($request, $bot);

            if(empty($input['section_id'])){
                //do we have a sectionId? (else null)
                $input['section_id'] = $this->botPropertyRepository->getSectionId('name', $bot->id);
            }

            $this->botPropertyRepository->create(['bot_id'=>$bot->id, 'name'=>'name', 'value'=>$input['name'], 'section_id'=>$input['section_id']]);

            Flash::success('Bot saved successfully.');
            // Commit the transaction
            DB::commit();
        } catch (Exception $e) {
            // An error occurred; cancel the transaction...
            DB::rollback();
            Log::error($e);

            //display generic error
            Flash::error('An error occurred - no changes have been made');
            //if admin display a little more info
            if(LemurPriv::isAdmin(Auth::user()) && (config('lemurbot.portal.show_detailed_error_messages'))){
                Flash::error($e->getMessage());
            }

            return redirect()->back();
        }

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(url('bots/'.$bot->slug."/edit"));
        }
    }

    /**
     * Display the specified Bot.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {

        $bot = $this->botRepository->getBySlug($slug);

        $this->authorize('view', $bot);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }
        $this->htmlTag = 'bots-readonly';

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        session(['target_bot' => $bot]);

        return view('lemurbot::bots.edit_all')->with(
            ['bot'=> $bot, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }



    /**
     * Show the form for editing the specified Bot.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {

        $bot = $this->botRepository->getBySlug($slug);

        $this->authorize('update', $bot);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        session(['target_bot' => $bot]);

        return view('lemurbot::bots.edit_all')->with(
            ['bot'=> $bot, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Update the specified Bot in storage.
     *
     * @param  string $slug
     * @param UpdateBotRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateBotRequest $request)
    {

        $bot = $this->botRepository->getBySlug($slug);

        $this->authorize('update', $bot);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        $input = $request->all();

        //if this a request to restore....
        if(!empty($input['restore'])){
            return $this->restore($bot->id, $request);
        }


        //start the transaction
        DB::beginTransaction();

        try {

            $bot = $this->botRepository->update($input, $bot->id);

            $this->uploadImage($request, $bot);

            Flash::success('Bot updated successfully.');
            // Commit the transaction
            DB::commit();
        } catch (Exception $e) {
            // An error occurred; cancel the transaction...
            DB::rollback();
            Log::error($e);

            //display generic error
            Flash::error('An error occurred - no changes have been made');
            //if admin display a little more info
            if(LemurPriv::isAdmin(Auth::user()) && (config('lemurbot.portal.show_detailed_error_messages'))){
                Flash::error($e->getMessage());
            }

            return redirect()->back();
        }


        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(url('bots/'.$bot->slug."/edit"));
        }
    }

    /**
     * Remove the specified Bot from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $bot = $this->botRepository->getBySlug($slug);

        $this->authorize('delete', $bot);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect()->back();
        }

        $this->botRepository->delete($bot->id);

        Flash::success('Bot deleted successfully.');

        return redirect()->back();
    }

    /**
 * Display all the properties for this bot in the tab
 *
 * @param  string $slug
 *
 * @return Response
 */
    public function botProperties($id)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'botProperties';
        $htmlTag = 'bot-properties';
        $title = 'Bot Properties';
        $resourceFolder = 'lemurbot::bot_properties';

        //set a list of all the available properties for the ui
        $recommendedAndSavedProperties = BotProperty::getFullPropertyList($bot->id);
        $allSections = Section::getAllSectionsForBotProperties();

        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        session(['target_bot' => $bot]);

        $botPropertySectionList = Section::where('type','BOT_PROPERTY')->orderBy('order')->pluck('name', 'slug');

        return view('lemurbot::bots.edit_all')->with([
            'bot'=> $bot,
            'botProperties'=> $recommendedAndSavedProperties,
            'link'=>$link,
            'htmlTag'=>$htmlTag,
            'title'=>$title,
            'resourceFolder'=>$resourceFolder,
            'botList'=>$botList,
            'botPropertySectionList'=>$botPropertySectionList,
            'allSections'=>$allSections]
        );
    }



    /**
     * Display all the properties for this bot in the tab
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function botCategoryGroups($id)
    {
        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'botCategoryGroups';
        $htmlTag = 'bot-category-groups';
        $title = 'Bot Category Groups';
        $resourceFolder = 'lemurbot::bot_category_groups';

        //set a list of all the available category groups for the ui
        $allCategoryGroups = BotCategoryGroup::getAllCategoryGroupsForBot($bot->id, $bot->language_id);
        $allSections = Section::getAllSectionsForCategoryGroups($allCategoryGroups);
        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        session(['target_bot' => $bot]);


        $categoryGroupSectionList = Section::where('type','CATEGORY_GROUP')->orderBy('order')->pluck('name', 'slug');



        return view('lemurbot::bots.edit_all')->with(
            ['bot'=> $bot,'categoryGroups'=> $allCategoryGroups,
            'link'=>$link, 'htmlTag'=>$htmlTag,
                'title'=>$title,
            'resourceFolder'=>$resourceFolder,
            'botList'=>$botList,
                'categoryGroupSectionList'=>$categoryGroupSectionList,
                'allSections'=>$allSections]
        );
    }


    /**
     * Display all the properties for this bot in the tab
     *
     * @param $id
     * @param string $conversationSlug
     * @return Response
     */
    public function conversations($id, $conversationSlug = 'list')
    {


        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'conversations';
        $htmlTag = 'conversations';
        $title = 'Conversations';
        $resourceFolder = 'lemurbot::conversations';

        $fullConversation=null;
        $conversationProperties = null;
        $conversationSources = null;
        $client = null;


        //get a list of the last 20 conversations and their count...
        $conversations = Conversation::where('bot_id', $bot->id)->latest('updated_at')->take(20)->get();

        //if we have a conversation...
        if ($conversations!==null) {
            if ($conversationSlug!='list') {
                //get the specific conversation for this bot
                $fullConversation = Conversation::where('slug', $conversationSlug)
                    ->where('bot_id', $bot->id)->latest('updated_at')->first();
                //get a list of the last 20 conversations and their count...
                $selectedConversation = Conversation::where('bot_id', $bot->id)->latest('updated_at')->take(20)->get();

                $conversations = $selectedConversation->merge($conversations);
            } else {
                //get all conversations for the bot we are looking at
                $fullConversation = Conversation::where('bot_id', $bot->id)->latest('updated_at')->first();
            }
            if ($fullConversation) {
                //get the properties for this conversation
                $conversationProperties = ConversationProperty::where('conversation_id', $fullConversation->id)
                    ->pluck('value', 'name');
                //get the properties for this conversation
                $client = Client::find($fullConversation->client_id);
                //get the sources for this conversation
                $conversationSources = ConversationSource::where('conversation_id', $fullConversation->id)->get();
            }
        }

        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        session(['target_bot' => $bot]);

        return view('lemurbot::bots.edit_all')->with(
            [
                'botList'=>$botList, 'bot'=> $bot,
                'conversations'=> $conversations,
                'fullConversation'=>$fullConversation,
                'targetConversationSlug' => ($fullConversation->slug??''),
                'client'=>$client,
                'conversationProperties'=> $conversationProperties,
                'conversationSources'=> $conversationSources,
                'link'=>$link, 'htmlTag'=>$htmlTag,
                'title'=>$title,
                'resourceFolder'=>$resourceFolder]
        );
    }


    /**
     * Display all the properties for this bot in the tab
     *
     * @param $id
     * @return Response
     */
    public function botPlugins($id)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'botPlugins';
        $htmlTag = 'bot-plugins';
        $title = 'Bot Plugins';
        $resourceFolder = 'lemurbot::bot_plugins';

        //set a list of all the available category groups for the ui
        $allWordSpellingGroups = BotWordSpellingGroup::getAllWordSpellingsGroupsForBot($bot->id);

        //set a list of all the available category groups for the ui
        $allBotPlugins = Plugin::getAllPluginsForBot($bot->id);

        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        $pluginList = Plugin::pluck('title', 'slug');

        session(['target_bot' => $bot]);

        return view('lemurbot::bots.edit_all')->with(
            ['bot'=> $bot,'wordSpellingGroups'=> $allWordSpellingGroups,
            'link'=>$link, 'htmlTag'=>$htmlTag, 'title'=>$title,
            'resourceFolder'=>$resourceFolder,
            'botList'=>$botList, 'pluginList'=>$pluginList, 'allBotPlugins'=>$allBotPlugins ]
        );
    }

    /**
     * Display all the keys for this bot in the tab
     *
     * @param $id
     * @return Response
     */
    public function botKeys($id)
    {

        $bot = $this->botRepository->find($id);


        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'botKeys';
        $htmlTag = 'bot-keys';
        $title = 'Bot Keys';
        $resourceFolder = 'bot_keys';

        //set a list of all keys for this bot
        $botKeys = BotKey::orderBy('name')->where('bot_id', $bot->id)->get();
        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        session(['target_bot' => $bot]);


        return view('lemurbot::bots.edit_all')->with(
            ['bot'=> $bot,'botKeys'=> $botKeys,
            'link'=>$link, 'htmlTag'=>$htmlTag,
                'title'=>$title,
            'resourceFolder'=>$resourceFolder,
            'botList'=>$botList]
        );
    }


    /**
     * Display all the allowed sites for this bot in the tab
     *
     * @param $id
     * @return Response
     */
    public function botSites($id)
    {

        $bot = $this->botRepository->find($id);


        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //to help with data testing and form settings
        $link = 'botAllowedSites';
        $htmlTag = 'bot-allowed-sites';
        $title = 'Bot Allowed Sites';
        $resourceFolder = 'lemurbot::bot_allowed_sites';

        //set a list of all keys for this bot
        $botAllowedSites = BotAllowedSite::orderBy('website_url')->where('bot_id', $bot->id)->get();
        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        session(['target_bot' => $bot]);


        return view('lemurbot::bots.edit_all')->with(
            ['bot'=> $bot,'botAllowedSites'=> $botAllowedSites,
                'link'=>$link, 'htmlTag'=>$htmlTag,
                'title'=>$title,
                'resourceFolder'=>$resourceFolder,
                'botList'=>$botList]
        );
    }

    /**
     * Display all the properties for this bot in the tab
     *
     * @param $id
     * @param BotStatsService $botStatsService
     * @return Response
     */
    public function stat_download(Request $request, $id, BotStatsService $botStatsService)
    {
        $bot = $this->botRepository->find($id);
        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }

        $from = $request->post('date_from');
        if(empty($from)){
            $from = Carbon::now()->subDay();
        }else{
            $from = Carbon::parse($from);
        }

        $to = $request->post('date_to');
        if(empty($to)){
            $to = Carbon::now();
        }else{
            $to = Carbon::parse($to);
        }
        $results = $botStatsService->getTurnByBotByConversationStats($bot->id, $from, $to);

        $filenameFrom = $from->format('YmdHis');
        $filenameTo = $to->format('YmdHis');

        $fileName = str_replace(' ','-','stats-'.$bot->slug.'-'.$filenameFrom.'-'.$filenameTo.'.csv');

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('BotId', 'BotName', 'ClientId', 'ConversationId', 'Referer', 'IP', 'TotalTurns');

        $callback = function() use($results, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($results as $result) {
                $row['BotId']  = $result->bot_slug;
                $row['BotName']    = $result->bot_name;
                $row['ClientId']    = $result->client_slug;
                $row['ConversationId']  = $result->conversation_slug;
                $row['Referer']  = $result->referer;
                $row['IP']  = $result->ip;
                $row['TotalTurns']  = $result->turns_total;
                fputcsv($file, array($row['BotId'], $row['BotName'], $row['ClientId'], $row['ConversationId'], $row['Referer'], $row['IP'], $row['TotalTurns']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);

    }


    /**
     * Display all the properties for this bot in the tab
     *
     * @param $id
     * @param BotStatsService $botStatsService
     * @return Response
     */
    public function stats($id, BotStatsService $botStatsService)
    {

        $bot = $this->botRepository->find($id);


        if (empty($bot)) {
            Flash::error('Bot not found');

            return redirect(route('bots.index'));
        }

        //ui settings
        $link = 'botStats';
        $htmlTag = 'bot-stats';
        $title = 'Bot Stats';
        $resourceFolder = 'lemurbot::bot_stats';

        //set a list of all keys for this bot
        $botKeys = BotKey::orderBy('name')->where('bot_id', $bot->id)->get();
        //list of bots for forms (but in this view we only want the bot we are looking at)
        $botList = Bot::where('id', $bot->id)->pluck('name', 'slug');

        session(['target_bot' => $bot]);

        return view('lemurbot::bots.edit_all')->with([
            'bot'=> $bot,
                'botKeys'=> $botKeys,
                'link'=>$link,
                'htmlTag'=>$htmlTag,
                'title'=>$title,
                'botList' => $botList,
                'resourceFolder'=>$resourceFolder,
                'botRatingCount'=>$bot->botRatingCount(),
                'botRatingAvg'=>$bot->botRatingAvg(),
                'yearlyConversationStat' =>   $botStatsService->getYearByMonthConversationStats($bot),
                'monthlyConversationStat' =>   $botStatsService->getMonthByDayConversationStats($bot),
                'allTimeConversationStat' =>   $botStatsService->getAllTimeConversationStats($bot),
                'todayConversationStat' =>   $botStatsService->getTodayConversationStats($bot),
                'yearlyTurnStat' =>   $botStatsService->getYearByMonthTurnStats($bot),
                'monthlyTurnStat' =>   $botStatsService->getMonthByDayTurnStats($bot),
                'allTimeTurnStat' =>   $botStatsService->getAllTimeTurnStats($bot),
                'todayTurnStat' =>   $botStatsService->getTodayTurnStats($bot),
                'daysInMonthKey' => $botStatsService->getDaysInMonthKey(),
                'monthsInYearKey' => $botStatsService->getMonthsInYearKey(),
                ]
        );
    }

    /**
     * Display all the properties for this bot in the tab
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function widgets($id)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }


        //to help with data testing and form settings
        $link = 'botWidgets';
        $htmlTag = 'bot-widgets';
        $title = 'Bot Widgets';
        $resourceFolder = 'lemurbot::bot_widgets';


        session(['target_bot' => $bot]);

        // get a nice list of word spelling groups
        return view('lemurbot::bots.edit_all')->with(
            ['bot'=> $bot,
                'link'=>$link,
                'htmlTag'=>$htmlTag,
                'title'=>$title,
                'resourceFolder'=>$resourceFolder
            ]
        );
    }

    /**
     * Display all the properties for this bot in the tab
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function chatForm($id)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }


        //to help with data testing and form settings
        $link = 'botChat';
        $htmlTag = 'bot-chat';
        $title = 'Bot Chat';
        $resourceFolder = 'lemurbot::bot_chat';

        session(['target_bot' => $bot]);

        // get a nice list of word spelling groups
        return view('lemurbot::bots.edit_all')->with(
            ['bot'=> $bot,
                'link'=>$link,
                'htmlTag'=>$htmlTag,
                'title'=>$title,
                'conversation'=>[],
                'resourceFolder'=>$resourceFolder
            ]
        );
    }


    /**
     * Display all the properties for this bot in the tab
     *
     * @param Request $request
     * @return Response
     */
    public function quickChat(Request $request)
    {
        $slug = $request->input('bot_id','');
        $bot = Bot::where('slug',$slug)->where('user_id', Auth::user()->id)->first();

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }else{
            return redirect(url('bot/'.$slug.'/chat'));
        }
    }

    /**
     * Initiate a talk to the bot...
     * @param CreateTalkRequest $request
     * @param TalkService $talkService
     * @return Factory|View
     * @throws Exception
     */
    public function chat($id, CreateTalkRequest $request, TalkService $talkService)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }


        //to help with data testing and form settings
        $link = 'botChat';
        $htmlTag = 'bot-chat';
        $title = 'Bot Chat';
        $resourceFolder = 'lemurbot::bot_chat';

        session(['target_bot' => $bot]);

        try {
            if (!empty($request->input('message'))) {
                $talkService->checkOwnerAccess($request);
                $talkService->validateRequest($request);


                //return the service and return the response and debug
                $parts = $talkService->run($request->input(), true);
                //get the latest conversations from the service
                $conversation=$talkService->getConversation();

                $res=$parts['response'];
                $debug=$parts['debug'];
                $flow=$parts['flow'];
                $allResponses=$parts['allResponses'];
            } else {
                $res=[];
                $conversation=[];
                $debug=[];
                $flow=[];
                $allResponses=[];
            }


            // get a nice list of word spelling groups
            return view('lemurbot::bots.edit_all')->with(
                ['bot'=> $bot,
                    'link'=>$link,
                    'htmlTag'=>$htmlTag,
                    'title'=>$title,
                    'resourceFolder'=>$resourceFolder,
                    'response'=>$res,
                    'conversation'=>$conversation,
                    'debug'=>$debug,
                    'flow'=>$flow,
                    'sentences' => $allResponses,
                ]
            );
        } catch (Exception $e) {
            // get a nice list of word spelling groups
            return view('lemurbot::bots.edit_all')->with(
                ['bot'=> $bot,
                    'link'=>$link,
                    'htmlTag'=>$htmlTag,
                    'title'=>$title,
                    'resourceFolder'=>$resourceFolder,
                    'response'=>['error'=>true,'message'=>$e->getMessage(),'line'=>$e->getLine(),'file'=>basename($e->getFile())],
                    'conversation'=>[],
                    'debug'=>[],
                    'sentences' => [],
                ]
            );
        }


    }


    /**
     * Display all the properties for this bot in the tab
     *
     * @param  string $slug
     *
     * @return Response
     */
    public function popupChatForm($id)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }


        //to help with data testing and form settings
        $link = 'popupChat';
        $htmlTag = 'popup-chat';
        $title = 'Popup Chat';
        $resourceFolder = 'lemurbot::bot_chat';

        session(['target_bot' => $bot]);

        // get a nice list of word spelling groups
        return view($resourceFolder.'.popup')->with(
            ['bot'=> $bot,
                'link'=>$link,
                'htmlTag'=>$htmlTag,
                'title'=>$title,
                'conversation'=>[],
                'resourceFolder'=>$resourceFolder
            ]
        );
    }

    /**
     * Initiate a talk to the bot...
     * @param CreateTalkRequest $request
     * @param TalkService $talkService
     * @return Factory|View
     * @throws Exception
     */
    public function popupChat($id, CreateTalkRequest $request, TalkService $talkService)
    {

        $bot = $this->botRepository->find($id);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }


        //to help with data testing and form settings
        $link = 'popupChat';
        $htmlTag = 'popup-chat';
        $title = 'Popup Chat';
        $resourceFolder = 'lemurbot::bot_chat';

        session(['target_bot' => $bot]);

        try {
            if (!empty($request->input('message'))) {
                $talkService->checkOwnerAccess($request);
                $talkService->validateRequest($request);


                //return the service and return the response and debug
                $parts = $talkService->run($request->input(), true);
                //get the latest conversations from the service
                $conversation=$talkService->getConversation();

                $res=$parts['response'];
                $allResponses=$parts['allResponses'];
            } else {
                $res=[];
                $conversation=[];
                $allResponses=[];
            }


            // get a nice list of word spelling groups
            return view($resourceFolder.'.popup')->with(
                ['bot'=> $bot,
                    'link'=>$link,
                    'htmlTag'=>$htmlTag,
                    'title'=>$title,
                    'resourceFolder'=>$resourceFolder,
                    'response'=>$res,
                    'conversation'=>$conversation,
                    'sentences' => $allResponses,
                ]
            );
        } catch (Exception $e) {
            // get a nice list of word spelling groups
            return view($resourceFolder.'.popup')->with(
                ['bot'=> $bot,
                    'link'=>$link,
                    'htmlTag'=>$htmlTag,
                    'title'=>$title,
                    'resourceFolder'=>$resourceFolder,
                    'response'=>['error'=>true,'message'=>$e->getMessage(),'line'=>$e->getLine(),'file'=>basename($e->getFile())],
                    'conversation'=>[],
                    'sentences' => [],
                ]
            );
        }


    }

    /**
     * @param $request
     * @param $bot
     */
    public function uploadImage($request, $bot)
    {


        if (!empty($request->file('image'))) {

            $filename = $bot->slug.".".$request->file('image')->extension();

            //store the file
            $request->file('image')->storeAs(
                'public/avatars', $filename
            );

            $bot->image = $filename;
            $bot->save();
            return $bot;
        }

        return $bot;
    }



    /**
     * Restore the a soft deleted it...
     *
     * @param Bot $bot
     * @param UpdateBotRequest $request
     *
     * @return Response
     * @throws Exception
     */
    public function restore($bot, UpdateBotRequest $request)
    {

        $this->authorize('update', $bot);

        $input = $request->all();

        if(!empty($input['restore'])){
            $this->botRepository->makeModel()->withTrashed()->where('id',$bot->id)->first()->restore();
        }

        if (empty($bot)) {
            Flash::error('Error restoring bot');
            return redirect(route('bots.index'));
        }

        Flash::success('Bot restored successfully.');

        if(!empty($input['redirect_url'])){
            return redirect($input['redirect_url']);
        }else{
            return redirect(route('bots.index'));
        }

    }


    /**
     * Remove the specified Bot from storage.
     *
     * @param  Bot $bot
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function forceDestroy($bot)
    {
        $this->authorize('forceDelete', $bot);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect()->back();
        }


        try {
            $this->botRepository->forceDelete($bot->id);
            Flash::success('Bot and related items permanently deleted successfully.');
        } catch (Exception $e) {

            // An error occurred; cancel the transaction...
            Log::error($e);

            //display generic error
            Flash::error('An error occurred - no changes have been made');
            //if admin display a little more info
            if(LemurPriv::isAdmin(Auth::user()) && (config('lemurbot.portal.show_detailed_error_messages'))){
                Flash::error($e->getMessage());
            }

        }

        return redirect()->back();


    }

    /**
     * Update the specified Bot in storage.
     *
     * @param  Bot $bot
     * @param UpdateBotSlugRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function slugUpdate($bot, UpdateBotSlugRequest $request)
    {

        $this->authorize('update', $bot);

        $inputAll=$request->all();

        $botCheck = $this->botRepository->getBySlug($inputAll['original_slug']);

        if (empty($bot)||empty($botCheck)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }

        if($botCheck->id != $bot->id){
            Flash::error('Bot slug mismatch');
            return redirect(route('bots.index'));
        }


        $input['slug'] = $inputAll['slug'];
        $bot = $this->botRepository->update($input, $bot->id);

        Flash::success('Bot slug updated successfully.');

        return redirect(route('bots.index'));



    }
}
