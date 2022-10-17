<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\SetDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateSetRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateSetRequest;
use LemurEngine\LemurBot\Repositories\SetRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Set;

class SetController extends AppBaseController
{
    private SetRepository $setRepository;

    //to help with data testing and form settings
    public string $link = 'sets';
    public string $htmlTag = 'sets';
    public string $title = 'Sets';
    public string $resourceFolder = 'lemurbot::sets';

    public function __construct(SetRepository $setRepo)
    {
        $this->middleware('auth');
        $this->setRepository = $setRepo;
    }

    /**
     * Display a listing of the Set.
     *
     * @param SetDataTable $setDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(SetDataTable $setDataTable)
    {
        $this->authorize('viewAny', Set::class);
        $setDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $setDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Set.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Set::class);
        return view('lemurbot::sets.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Store a newly created Set in storage.
     *
     * @param CreateSetRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateSetRequest $request)
    {
        $this->authorize('create', Set::class);
        $input = $request->all();

        $set = $this->setRepository->create($input);

        Flash::success('Set saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('sets.index'));
        }
    }

    /**
     * Display the specified Set.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $set = $this->setRepository->getBySlug($slug);

        $this->authorize('view', $set);

        if (empty($set)) {
            Flash::error('Set not found');

            return redirect(route('sets.index'));
        }

        return view('lemurbot::sets.show')->with(
            ['set'=>$set, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Set.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $set = $this->setRepository->getBySlug($slug);

        $this->authorize('update', $set);

        if (empty($set)) {
            Flash::error('Set not found');

            return redirect(route('sets.index'));
        }

        return view('lemurbot::sets.edit')->with(
            ['set'=> $set, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Set in storage.
     *
     * @param  string $slug
     * @param UpdateSetRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateSetRequest $request)
    {
        $set = $this->setRepository->getBySlug($slug);

        $this->authorize('update', $set);

        if (empty($set)) {
            Flash::error('Set not found');

            return redirect(route('sets.index'));
        }

        $input = $request->all();

        $set = $this->setRepository->update($input, $set->id);

        Flash::success('Set updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('sets.index'));
        }
    }

    /**
     * Remove the specified Set from storage.
     *
     * @param  slug $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $set = $this->setRepository->getBySlug($slug);

        $this->authorize('delete', $set);

        if (empty($set)) {
            Flash::error('Set not found');

            return redirect(route('sets.index'));
        }

        $this->setRepository->delete($set->id);

        Flash::success('Set deleted successfully.');

        return redirect()->back();
    }
}
