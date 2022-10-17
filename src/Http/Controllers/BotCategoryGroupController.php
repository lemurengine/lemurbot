<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\BotCategoryGroupDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateBotCategoryGroupRequest;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Models\CategoryGroup;
use LemurEngine\LemurBot\Repositories\BotCategoryGroupRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\BotCategoryGroup;

class BotCategoryGroupController extends AppBaseController
{
    private BotCategoryGroupRepository $botCategoryGroupRepository;

    //to help with data testing and form settings
    public string $link = 'botCategoryGroups';
    public string $htmlTag = 'bot-category-groups';
    public string $title = 'Bot Category Groups';
    public string $resourceFolder = 'lemurbot::bot_category_groups';

    public function __construct(BotCategoryGroupRepository $botCategoryGroupRepo)
    {
        $this->middleware('auth');
        $this->botCategoryGroupRepository = $botCategoryGroupRepo;
    }

    /**
     * Display a listing of the BotCategoryGroup.
     *
     * @param BotCategoryGroupDataTable $botCategoryGroupDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(BotCategoryGroupDataTable $botCategoryGroupDataTable)
    {
        $this->authorize('viewAny', BotCategoryGroup::class);
        $botCategoryGroupDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $botCategoryGroupDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new BotCategoryGroup.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', BotCategoryGroup::class);

        $botList = Bot::orderBy('name')->pluck('name', 'slug');
        $categoryGroupList = CategoryGroup::where('is_master', 1)
            ->orWhere('user_id', Auth::user()->id)->orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::bot_category_groups.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'botList'=>$botList,
            'categoryGroupList'=>$categoryGroupList]
        );
    }

    /**
     * Store a newly created BotCategoryGroup in storage.
     *
     * @param CreateBotCategoryGroupRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateBotCategoryGroupRequest $request)
    {

        $this->authorize('create', BotCategoryGroup::class);
        $input = $request->all();

        if (!empty($input['bulk'])) {
            $this->botCategoryGroupRepository->bulkCreate($input);
            Flash::success('Bot Category Groups updated and saved successfully.');
        } else {
            $this->botCategoryGroupRepository->createOrUpdate($input);
            Flash::success('Bot Category Group saved successfully.');
        }

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('botCategoryGroups.index'));
        }
    }

    /**
     * Display the specified BotCategoryGroup.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $botCategoryGroup = $this->botCategoryGroupRepository->getBySlug($slug);

        $this->authorize('view', $botCategoryGroup);

        if (empty($botCategoryGroup)) {
            Flash::error('Bot Category Group not found');

            return redirect(route('botCategoryGroups.index'));
        }

        return view('lemurbot::bot_category_groups.show')->with(
            ['botCategoryGroup'=>$botCategoryGroup, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }


    /**
     * Show the form for creating a new ClientCategory.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {

        return view('lemurbot::bot_category_groups.edit')->with(
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
     * Remove the specified BotCategoryGroup from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $botCategoryGroup = $this->botCategoryGroupRepository->getBySlug($slug);

        $this->authorize('delete', $botCategoryGroup);

        if (empty($botCategoryGroup)) {
            Flash::error('Bot Category Group not found');

            return redirect(route('botCategoryGroups.index'));
        }

        $this->botCategoryGroupRepository->delete($botCategoryGroup->id);

        Flash::success('Bot Category Group deleted successfully.');

        return redirect()->back();
    }
}
