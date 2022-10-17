<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\CategoryGroupDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateCategoryGroupRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateCategoryGroupRequest;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\Section;
use LemurEngine\LemurBot\Repositories\CategoryGroupRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\CategoryGroup;

class CategoryGroupController extends AppBaseController
{
    private CategoryGroupRepository $categoryGroupRepository;

    //to help with data testing and form settings
    public string $link = 'categoryGroups';
    public string $htmlTag = 'category-groups';
    public string $title = 'Category Groups';
    public string $resourceFolder = 'lemurbot::category_groups';

    public function __construct(CategoryGroupRepository $categoryGroupRepo)
    {
        $this->middleware('auth');
        $this->categoryGroupRepository = $categoryGroupRepo;
    }

    /**
     * Display a listing of the CategoryGroup.
     *
     * @param CategoryGroupDataTable $categoryGroupDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(CategoryGroupDataTable $categoryGroupDataTable)
    {
        $this->authorize('viewAny', CategoryGroup::class);
        $categoryGroupDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $categoryGroupDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new CategoryGroup.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', CategoryGroup::class);

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        $categoryGroupSectionList = Section::where('type','CATEGORY_GROUP')->orderBy('order')->pluck('name', 'slug');

        return view('lemurbot::category_groups.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList,
                'categoryGroupSectionList'=>$categoryGroupSectionList]
        );
    }

    /**
     * Store a newly created CategoryGroup in storage.
     *
     * @param CreateCategoryGroupRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateCategoryGroupRequest $request)
    {
        $this->authorize('create', CategoryGroup::class);
        $input = $request->all();

        $categoryGroup = $this->categoryGroupRepository->create($input);

        Flash::success('Category Group saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('categoryGroups.index'));
        }
    }

    /**
     * Display the specified CategoryGroup.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $categoryGroup = $this->categoryGroupRepository->getBySlug($slug);

        $this->authorize('view', $categoryGroup);

        if (empty($categoryGroup)) {
            Flash::error('Category Group not found');

            return redirect(route('categoryGroups.index'));
        }

        return view('lemurbot::category_groups.show')->with(
            ['categoryGroup'=>$categoryGroup, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified CategoryGroup.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {

        $categoryGroup = $this->categoryGroupRepository->getBySlug($slug);

        $this->authorize('update', $categoryGroup);

        if (empty($categoryGroup)) {
            Flash::error('Category Group not found');

            return redirect(route('categoryGroups.index'));
        }

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        $categoryGroupSectionList = Section::where('type','CATEGORY_GROUP')->orderBy('order')->pluck('name', 'slug');

        return view('lemurbot::category_groups.edit')->with(
            ['categoryGroup'=> $categoryGroup,
            'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList,
                'categoryGroupSectionList'=>$categoryGroupSectionList]
        );
    }

    /**
     * Update the specified CategoryGroup in storage.
     *
     * @param  string $slug
     * @param UpdateCategoryGroupRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateCategoryGroupRequest $request)
    {
        $categoryGroup = $this->categoryGroupRepository->getBySlug($slug);

        $this->authorize('update', $categoryGroup);

        if (empty($categoryGroup)) {
            Flash::error('Category Group not found');

            return redirect(route('categoryGroups.index'));
        }

        $input = $request->all();

        $categoryGroup = $this->categoryGroupRepository->update($input, $categoryGroup->id);

        Flash::success('Category Group updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('categoryGroups.index'));
        }
    }

    /**
     * Remove the specified CategoryGroup from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $categoryGroup = $this->categoryGroupRepository->getBySlug($slug);

        $this->authorize('delete', $categoryGroup);

        if (empty($categoryGroup)) {
            Flash::error('Category Group not found');

            return redirect(route('categoryGroups.index'));
        }


        $this->categoryGroupRepository->delete($categoryGroup->id);

        Flash::success('Category Group deleted successfully.');

        return redirect()->back();
    }
}
