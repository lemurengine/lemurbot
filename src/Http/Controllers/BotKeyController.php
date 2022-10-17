<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Exception;
use LemurEngine\LemurBot\DataTables\BotKeyDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateBotKeyRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateBotKeyRequest;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Repositories\BotKeyRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\BotKey;

class BotKeyController extends AppBaseController
{
    private BotKeyRepository $botKeyRepository;

    //to help with data testing and form settings
    public string $link = 'botKeys';
    public string $htmlTag = 'bot-keys';
    public string $title = 'Bot Keys';
    public string $resourceFolder = 'lemurbot::bot_keys';

    public function __construct(BotKeyRepository $botKeyRepo)
    {
        $this->middleware('auth');
        $this->botKeyRepository = $botKeyRepo;
    }

    /**
     * Display a listing of the BotKey.
     *
     * @param BotKeyDataTable $botKeyDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(BotKeyDataTable $botKeyDataTable)
    {
        $this->authorize('viewAny', BotKey::class);
        $botKeyDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botKeyDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new BotKey.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', BotKey::class);

        $botList = Bot::orderBy('name')->get()->pluck('full_name', 'slug');

        return view('lemurbot::bot_keys.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder, 'botList'=>$botList]
        );
    }

    /**
     * Store a newly created BotKey in storage.
     *
     * @param CreateBotKeyRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateBotKeyRequest $request)
    {
        $this->authorize('create', BotKey::class);

        $input = $request->all();

        $botKey = $this->botKeyRepository->create($input);

        Flash::success('Bot Key saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('botKeys.index'));
        }
    }

    /**
     * Display the specified BotKey.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $botKey = $this->botKeyRepository->getBySlug($slug);

        $this->authorize('view', $botKey);

        if (empty($botKey)) {
            Flash::error('Bot Key not found');

            return redirect(route('botKeys.index'));
        }

        return view('lemurbot::bot_keys.show')->with(
            ['botKey'=>$botKey, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified BotKey.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $botKey = $this->botKeyRepository->getBySlug($slug);

        $this->authorize('update', $botKey);

        if (empty($botKey)) {
            Flash::error('Bot Key not found');

            return redirect(route('botKeys.index'));
        }

        $botList = Bot::orderBy('name')->get()->pluck('full_name', 'slug');

        return view('lemurbot::bot_keys.edit')->with(
            ['botKey'=> $botKey, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder, 'botList'=>$botList]
        );
    }

    /**
     * Update the specified BotKey in storage.
     *
     * @param string $slug
     * @param UpdateBotKeyRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateBotKeyRequest $request)
    {
        $botKey = $this->botKeyRepository->getBySlug($slug);

        $this->authorize('update', $botKey);

        if (empty($botKey)) {
            Flash::error('Bot Key not found');

            return redirect(route('botKeys.index'));
        }

        $botKeyId = $botKey->id;

        $input = $request->all();

        $botKey = $this->botKeyRepository->update($input, $botKeyId);

        Flash::success('Bot Key updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('botKeys.index'));
        }
    }

    /**
     * Remove the specified BotKey from storage.
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
        $botKey = $this->botKeyRepository->getBySlug($slug);

        $this->authorize('delete', $botKey);

        if (empty($botKey)) {
            Flash::error('Bot Key not found');
            return redirect()->back();
        }

        $botKeyId = $botKey->id;

        $this->botKeyRepository->delete($botKeyId);

        Flash::success('Bot Key deleted successfully.');

        return redirect()->back();
    }
}
