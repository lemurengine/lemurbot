<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Exception;
use LemurEngine\LemurBot\DataTables\WildcardDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateWildcardRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateWildcardRequest;
use LemurEngine\LemurBot\Repositories\WildcardRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Wildcard;

class WildcardController extends AppBaseController
{
    private WildcardRepository $wildcardRepository;

    //to help with data testing and form settings
    public string $link = 'wildcards';
    public string $htmlTag = 'wildcards';
    public string $title = 'Wildcards';
    public string $resourceFolder = 'lemurbot::wildcards';

    public function __construct(WildcardRepository $wildcardRepo)
    {
        $this->middleware('auth');
        $this->wildcardRepository = $wildcardRepo;
    }

    /**
     * Display a listing of the Wildcard.
     *
     * @param WildcardDataTable $wildcardDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(WildcardDataTable $wildcardDataTable)
    {
        $this->authorize('viewAny', Wildcard::class);
        $wildcardDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $wildcardDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the default message for
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {

        return view('lemurbot::wildcards.create')->with(
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
     * Display the specified Wildcard.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $wildcard = $this->wildcardRepository->getBySlug($slug);

        $this->authorize('view', $wildcard);

        if (empty($wildcard)) {
            Flash::error('Wildcard not found');

            return redirect(route('wildcards.index'));
        }

        return view('lemurbot::wildcards.show')->with(
            ['wildcard'=>$wildcard, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Wildcard.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit()
    {
        return view('lemurbot::wildcards.edit')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Directly updating new items is not allowed
     *
     */
    public function update()
    {
        //we do not updating items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified Wildcard from storage.
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
        $wildcard = $this->wildcardRepository->getBySlug($slug);

        $this->authorize('delete', $wildcard);

        if (empty($wildcard)) {
            Flash::error('Wildcard not found');

            return redirect(route('wildcards.index'));
        }

        $wildcardId = $wildcard->id;

        $this->wildcardRepository->delete($wildcardId);

        Flash::success('Wildcard deleted successfully.');

        return redirect()->back();
    }
}
