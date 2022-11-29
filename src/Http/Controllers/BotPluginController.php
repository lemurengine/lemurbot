<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Exception;
use LemurEngine\LemurBot\DataTables\BotPluginDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateBotPluginRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateBotPluginRequest;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\Plugin;
use LemurEngine\LemurBot\Repositories\BotPluginRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\BotPlugin;

class BotPluginController extends AppBaseController
{
    private BotPluginRepository $botPluginRepository;

    //to help with data testing and form settings
    public string $link = 'botPlugins';
    public string $htmlTag = 'bot-plugins';
    public string $title = 'Bot Plugins';
    public string $resourceFolder = 'lemurbot::bot_plugins';

    public function __construct(BotPluginRepository $botPluginRepo)
    {
        $this->middleware('auth');
        $this->botPluginRepository = $botPluginRepo;
    }

    /**
     * Display a listing of the BotPlugin.
     *
     * @param BotPluginDataTable $botPluginDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(BotPluginDataTable $botPluginDataTable)
    {
        $this->authorize('viewAny', BotPlugin::class);
        $botPluginDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botPluginDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new BotPlugin.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', BotPlugin::class);

        $botList = Bot::myEditableItems()->orderBy('name')->get()->pluck('full_name', 'slug');
        $pluginList = Plugin::myEditableItems()->orderBy('title')->get()->pluck('full_title', 'slug');

        return view('lemurbot::bot_plugins.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder, 'botList'=>$botList, 'pluginList'=>$pluginList]
        );
    }

    /**
     * Store a newly created BotPlugin in storage.
     *
     * @param CreateBotPluginRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateBotPluginRequest $request)
    {
        $this->authorize('create', BotPlugin::class);

        $input = $request->all();


        if (!empty($input['bulk'])) {
            $this->botPluginRepository->bulkCreate($input);
            Flash::success('Bot plugins saved successfully.');
        } else {
            $this->botPluginRepository->create($input);
            Flash::success('Bot plugin saved successfully.');
        }

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('botPlugins.index'));
        }
    }

    /**
     * Display the specified BotPlugin.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $botPlugin = $this->botPluginRepository->getBySlug($slug);

        $this->authorize('view', $botPlugin);

        if (empty($botPlugin)) {
            Flash::error('Bot Plugin not found');

            return redirect(route('botPlugins.index'));
        }

        return view('lemurbot::bot_plugins.show')->with(
            ['botPlugin'=>$botPlugin, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified BotPlugin.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $botPlugin = $this->botPluginRepository->getBySlug($slug);

        $this->authorize('update', $botPlugin);

        if (empty($botPlugin)) {
            Flash::error('Bot Plugin not found');

            return redirect(route('botPlugins.index'));
        }

        $botList = Bot::myEditableItems()->orderBy('name')->get()->pluck('full_name', 'slug');
        $pluginList = Plugin::myEditableItems()->orderBy('title')->get()->pluck('full_title', 'slug');

        return view('lemurbot::bot_plugins.edit')->with(
            ['botPlugin'=> $botPlugin, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder, 'botList'=>$botList, 'pluginList'=>$pluginList]
        );
    }

    /**
     * Update the specified BotPlugin in storage.
     *
     * @param string $slug
     * @param UpdateBotPluginRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateBotPluginRequest $request)
    {
        //we do not update these items
        //we should have told the user to delete and recreate
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified BotPlugin from storage.
     *
     * @param string $slug
     *
     * @throws Exception
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $botPlugin = $this->botPluginRepository->getBySlug($slug);

        $this->authorize('delete', $botPlugin);

        if (empty($botPlugin)) {
            Flash::error('Bot Plugin not found');
            return redirect()->back();
        }

        $botPluginId = $botPlugin->id;

        //simply no point in soft deleting and restoring we might as well just hard delete
        $this->botPluginRepository->forceDelete($botPluginId);

        Flash::success('Bot Plugin deleted successfully.');

        return redirect()->back();
    }
}
