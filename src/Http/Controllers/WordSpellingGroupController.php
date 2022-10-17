<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\WordSpellingGroupDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateWordSpellingGroupRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateWordSpellingGroupRequest;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Repositories\WordSpellingGroupRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\WordSpellingGroup;

class WordSpellingGroupController extends AppBaseController
{
    private WordSpellingGroupRepository $wordSpellingGroupRepository;

    //to help with data testing and form settings
    public string $link = 'wordSpellingGroups';
    public string $htmlTag = 'word-spelling-groups';
    public string $title = 'Word Spelling Groups';
    public string $resourceFolder = 'lemurbot::word_spelling_groups';

    public function __construct(WordSpellingGroupRepository $wordSpellingGroupRepo)
    {
        $this->middleware('auth');
        $this->wordSpellingGroupRepository = $wordSpellingGroupRepo;
    }

    /**
     * Display a listing of the WordSpellingGroup.
     *
     * @param WordSpellingGroupDataTable $wordSpellingGroupDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(WordSpellingGroupDataTable $wordSpellingGroupDataTable)
    {
        $this->authorize('viewAny', WordSpellingGroup::class);
        $wordSpellingGroupDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $wordSpellingGroupDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new WordSpellingGroup.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', WordSpellingGroup::class);

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::word_spelling_groups.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Store a newly created WordSpellingGroup in storage.
     *
     * @param CreateWordSpellingGroupRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateWordSpellingGroupRequest $request)
    {
        $this->authorize('create', WordSpellingGroup::class);
        $input = $request->all();

        $wordSpellingGroup = $this->wordSpellingGroupRepository->create($input);

        Flash::success('Word Spelling Group saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('wordSpellingGroups.index'));
        }
    }

    /**
     * Display the specified WordSpellingGroup.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $wordSpellingGroup = $this->wordSpellingGroupRepository->getBySlug($slug);
        $this->authorize('view', $wordSpellingGroup);

        if (empty($wordSpellingGroup)) {
            Flash::error('Word Spelling Group not found');

            return redirect(route('wordSpellingGroups.index'));
        }

        return view('lemurbot::word_spelling_groups.show')->with(
            ['wordSpellingGroup'=>$wordSpellingGroup, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified WordSpellingGroup.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $wordSpellingGroup = $this->wordSpellingGroupRepository->getBySlug($slug);

        $this->authorize('update', $wordSpellingGroup);

        if (empty($wordSpellingGroup)) {
            Flash::error('Word Spelling Group not found');

            return redirect(route('wordSpellingGroups.index'));
        }

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::word_spelling_groups.edit')->with(
            ['wordSpellingGroup'=> $wordSpellingGroup,
            'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Update the specified WordSpellingGroup in storage.
     *
     * @param  string $slug
     * @param UpdateWordSpellingGroupRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateWordSpellingGroupRequest $request)
    {
        $wordSpellingGroup = $this->wordSpellingGroupRepository->getBySlug($slug);
        $this->authorize('update', $wordSpellingGroup);

        if (empty($wordSpellingGroup)) {
            Flash::error('Word Spelling Group not found');

            return redirect(route('wordSpellingGroups.index'));
        }

        $input = $request->all();

        $wordSpellingGroup = $this->wordSpellingGroupRepository->update($input, $wordSpellingGroup->id);

        Flash::success('Word Spelling Group updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('wordSpellingGroups.index'));
        }
    }

    /**
     * Remove the specified WordSpellingGroup from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $wordSpellingGroup = $this->wordSpellingGroupRepository->getBySlug($slug);

        $this->authorize('delete', $wordSpellingGroup);

        if (empty($wordSpellingGroup)) {
            Flash::error('Word Spelling Group not found');

            return redirect(route('wordSpellingGroups.index'));
        }

        $this->wordSpellingGroupRepository->delete($wordSpellingGroup->id);

        Flash::success('Word Spelling Group deleted successfully.');

        return redirect()->back();
    }
}
