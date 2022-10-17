<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\MachineLearntCategoryDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateMachineLearntCategoryRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateMachineLearntCategoryRequest;
use LemurEngine\LemurBot\Models\Bot;
use LemurEngine\LemurBot\Repositories\MachineLearntCategoryRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\MachineLearntCategory;

class MachineLearntCategoryController extends AppBaseController
{
    private MachineLearntCategoryRepository $machineLearntCategoryRepository;

    //to help with data testing and form settings
    public string $link = 'machineLearntCategories';
    public string $htmlTag = 'machine-learnt-categories';
    public string $title = 'Machine Learnt Categories';
    public string $resourceFolder = 'lemurbot::machine_learnt_categories';

    public function __construct(MachineLearntCategoryRepository $machineLearntCategoryRepo)
    {
        $this->middleware('auth');
        $this->machineLearntCategoryRepository = $machineLearntCategoryRepo;
    }

    /**
     * Display a listing of the MachineLearntCategory.
     *
     * @param MachineLearntCategoryDataTable $machineLearntCategoryDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(MachineLearntCategoryDataTable $machineLearntCategoryDataTable)
    {
        $this->authorize('viewAny', MachineLearntCategory::class);
        $machineLearntCategoryDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $machineLearntCategoryDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new MachineLearntCategory.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {

        return view('lemurbot::machine_learnt_categories.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }


    /**
     * Directly saving new items is not allowed
     *
     */
    public function store()
    {
        //we do not store items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Display the specified MachineLearntCategory.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $machineLearntCategory = $this->machineLearntCategoryRepository->getBySlug($slug);

        $this->authorize('view', $machineLearntCategory);

        if (empty($machineLearntCategory)) {
            Flash::error('Machine Learnt Category not found');

            return redirect(route('machineLearntCategories.index'));
        }

        return view('lemurbot::.show')->with(
            ['machineLearntCategory'=>$machineLearntCategory, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified MachineLearntCategory.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $machineLearntCategory = $this->machineLearntCategoryRepository->getBySlug($slug);

        $this->authorize('update', $machineLearntCategory);

        if (empty($machineLearntCategory)) {
            Flash::error('Machine Learnt Category not found');

            return redirect(route('machineLearntCategories.index'));
        }

        $botList = Bot::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::machine_learnt_categories.edit')->with(
            ['machineLearntCategory'=> $machineLearntCategory,
            'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'botList'=>$botList]
        );
    }

    /**
     * Update the specified MachineLearntCategory in storage.
     *
     * @param  string $slug
     * @param UpdateMachineLearntCategoryRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateMachineLearntCategoryRequest $request)
    {
        $machineLearntCategory = $this->machineLearntCategoryRepository->getBySlug($slug);

        $this->authorize('update', $machineLearntCategory);

        if (empty($machineLearntCategory)) {
            Flash::error('Machine Learnt Category not found');

            return redirect(route('machineLearntCategories.index'));
        }

        $input = $request->all();

        $machineLearntCategory = $this->machineLearntCategoryRepository->update($input, $machineLearntCategory->id);

        Flash::success('Machine Learnt Category updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('machineLearntCategories.index'));
        }
    }

    /**
     * Remove the specified MachineLearntCategory from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $machineLearntCategory = $this->machineLearntCategoryRepository->getBySlug($slug);

        $this->authorize('delete', $machineLearntCategory);

        if (empty($machineLearntCategory)) {
            Flash::error('Machine Learnt Category not found');

            return redirect(route('machineLearntCategories.index'));
        }

        $this->machineLearntCategoryRepository->delete($machineLearntCategory->id);

        Flash::success('Machine Learnt Category deleted successfully.');

        return redirect()->back();
    }
}
