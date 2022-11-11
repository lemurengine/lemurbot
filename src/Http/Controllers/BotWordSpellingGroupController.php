<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\BotWordSpellingGroupDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateBotWordSpellingGroupRequest;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\WordSpellingGroup;
use LemurEngine\LemurBot\Repositories\BotWordSpellingGroupRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\BotWordSpellingGroup;

class BotWordSpellingGroupController extends AppBaseController
{
    private BotWordSpellingGroupRepository $botWordSpellingGroupRepository;

    //to help with data testing and form settings
    public string $link = 'botWordSpellingGroups';
    public string $htmlTag = 'bot-word-spelling-groups';
    public string $title = 'Bot Word Spelling Groups';
    public string $resourceFolder = 'lemurbot::bot_word_spelling_groups';

    public function __construct(BotWordSpellingGroupRepository $botWordSpellingGroupRepo)
    {
        $this->middleware('auth');
        $this->botWordSpellingGroupRepository = $botWordSpellingGroupRepo;
    }

    /**
     * Display a listing of the BotWordSpellingGroup.
     *
     * @param BotWordSpellingGroupDataTable $botWordSpellingGroupDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(BotWordSpellingGroupDataTable $botWordSpellingGroupDataTable)
    {
        $this->authorize('viewAny', BotWordSpellingGroup::class);
        $botWordSpellingGroupDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botWordSpellingGroupDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new BotWordSpellingGroup.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', BotWordSpellingGroup::class);

        $botList = Bot::orderBy('name')->pluck('name', 'slug');
        $wordSpellingGroupList = WordSpellingGroup::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::bot_word_spelling_groups.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'botList'=>$botList,
            'wordSpellingGroupList'=>$wordSpellingGroupList]
        );
    }

    /**
     * Store a newly created BotWordSpellingGroup in storage.
     *
     * @param CreateBotWordSpellingGroupRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateBotWordSpellingGroupRequest $request)
    {
        $this->authorize('create', BotWordSpellingGroup::class);
        $input = $request->all();

        if (!empty($input['bulk'])) {
            $this->botWordSpellingGroupRepository->bulkCreate($input);
            Flash::success('Bot Word Spelling Groups updated and saved successfully.');
        } else {
            $this->botWordSpellingGroupRepository->createOrUpdate($input);
            Flash::success('Bot Word Spelling Groups saved successfully.');
        }

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('botWordSpellingGroups.index'));
        }
    }

    /**
     * Display the specified BotWordSpellingGroup.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {

        $botWordSpellingGroup = $this->botWordSpellingGroupRepository->getBySlug($slug);

        $this->authorize('view', $botWordSpellingGroup);

        if (empty($botWordSpellingGroup)) {
            Flash::error('Bot Word Spelling Group not found');

            return redirect(route('botWordSpellingGroups.index'));
        }

        return view('lemurbot::bot_word_spelling_groups.show')->with(
            ['botWordSpellingGroup'=>$botWordSpellingGroup, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified BotWordSpellingGroup.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit()
    {
        return view('lemurbot::bot_word_spelling_groups.edit')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Directly updating items is not allowed
     *
     */
    public function update()
    {
        //we do not store items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified BotWordSpellingGroup from storage.
     *
     * @param  int $id
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $botWordSpellingGroup = $this->botWordSpellingGroupRepository->getBySlug($slug);

        $this->authorize('delete', $botWordSpellingGroup);

        if (empty($botWordSpellingGroup)) {
            Flash::error('Bot Word Spelling Group not found');

            return redirect(route('botWordSpellingGroups.index'));
        }

        $this->botWordSpellingGroupRepository->delete($botWordSpellingGroup->id);

        Flash::success('Bot Word Spelling Group deleted successfully.');

        return redirect()->back();
    }
}
