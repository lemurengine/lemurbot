<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LemurEngine\LemurBot\DataTables\BotAllowedSiteDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateBotAllowedSiteRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateBotAllowedSiteRequest;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Repositories\BotAllowedSiteRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\BotAllowedSite;

class BotAllowedSiteController extends AppBaseController
{
    private BotAllowedSiteRepository $botAllowedSiteRepository;

    //to help with data testing and form settings
    public string $link = 'botAllowedSites';
    public string $htmlTag = 'bot-allowed-sites';
    public string $title = 'Bot Allowed Sites';
    public string $resourceFolder = 'lemurbot::bot_allowed_sites';

    public function __construct(BotAllowedSiteRepository $botAllowedSiteRepo)
    {
        $this->middleware('auth');
        $this->botAllowedSiteRepository = $botAllowedSiteRepo;
    }

    /**
     * Display a listing of the BotAllowedSite.
     *
     * @param BotAllowedSiteDataTable $botAllowedSiteDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(BotAllowedSiteDataTable $botAllowedSiteDataTable)
    {
        $this->authorize('viewAny', BotAllowedSite::class);
        $botAllowedSiteDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botAllowedSiteDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new BotAllowedSite.
     *
     * @return View|Factory
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', BotAllowedSite::class);

        $botList = Bot::orderBy('name')->get()->pluck('full_name', 'slug');

        return view('lemurbot::bot_allowed_sites.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder, 'botList'=>$botList]
        );
    }

    /**
     * Store a newly created BotAllowedSite in storage.
     *
     * @param CreateBotAllowedSiteRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateBotAllowedSiteRequest $request)
    {
        $this->authorize('create', BotAllowedSite::class);

        $input = $request->all();

        $botAllowedSite = $this->botAllowedSiteRepository->create($input);

        Flash::success('Bot Allowed Site saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('botAllowedSites.index'));
        }
    }

    /**
     * Display the specified BotAllowedSite.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $botAllowedSite = $this->botAllowedSiteRepository->getBySlug($slug);

        $this->authorize('view', $botAllowedSite);

        if (empty($botAllowedSite)) {
            Flash::error('Bot Allowed Site not found');

            return redirect(route('botAllowedSites.index'));
        }

        return view('lemurbot::bot_allowed_sites.show')->with(
            ['botAllowedSite'=>$botAllowedSite, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified BotAllowedSite.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $botAllowedSite = $this->botAllowedSiteRepository->getBySlug($slug);

        $this->authorize('update', $botAllowedSite);

        if (empty($botAllowedSite)) {
            Flash::error('Bot Allowed Site not found');

            return redirect(route('botAllowedSites.index'));
        }

        $botList = Bot::orderBy('name')->get()->pluck('full_name', 'slug');

        return view('lemurbot::bot_allowed_sites.edit')->with(
            ['botAllowedSite'=> $botAllowedSite, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder, 'botList'=>$botList]
        );
    }

    /**
     * Update the specified BotAllowedSite in storage.
     *
     * @param string $slug
     * @param UpdateBotAllowedSiteRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateBotAllowedSiteRequest $request)
    {
        $botAllowedSite = $this->botAllowedSiteRepository->getBySlug($slug);

        $this->authorize('update', $botAllowedSite);

        if (empty($botAllowedSite)) {
            Flash::error('Bot Allowed Site not found');

            return redirect(route('botAllowedSites.index'));
        }

        $botAllowedSiteId = $botAllowedSite->id;

        $input = $request->all();

        $botAllowedSite = $this->botAllowedSiteRepository->update($input, $botAllowedSiteId);

        Flash::success('Bot Allowed Site updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('botAllowedSites.index'));
        }
    }

    /**
     * Remove the specified BotAllowedSite from storage.
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
        $botAllowedSite = $this->botAllowedSiteRepository->getBySlug($slug);

        $this->authorize('delete', $botAllowedSite);

        if (empty($botAllowedSite)) {
            Flash::error('Bot Allowed Site not found');
            return redirect()->back();
        }

        $botAllowedSiteId = $botAllowedSite->id;

        $this->botAllowedSiteRepository->delete($botAllowedSiteId);

        Flash::success('Bot Allowed Site deleted successfully.');

        return redirect()->back();
    }
}
