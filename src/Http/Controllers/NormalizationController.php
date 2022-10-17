<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\NormalizationDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateNormalizationRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateNormalizationRequest;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Repositories\NormalizationRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Normalization;

class NormalizationController extends AppBaseController
{
    private NormalizationRepository $normalizationRepository;

    //to help with data testing and form settings
    public string $link = 'normalizations';
    public string $htmlTag = 'normalizations';
    public string $title = 'Normalizations';
    public string $resourceFolder = 'lemurbot::normalizations';

    public function __construct(NormalizationRepository $normalizationRepo)
    {
        $this->middleware('auth');
        $this->normalizationRepository = $normalizationRepo;
    }

    /**
     * Display a listing of the Normalization.
     *
     * @param NormalizationDataTable $normalizationDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(NormalizationDataTable $normalizationDataTable)
    {
        $this->authorize('viewAny', Normalization::class);
        $normalizationDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $normalizationDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Normalization.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Normalization::class);

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::normalizations.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Store a newly created Normalization in storage.
     *
     * @param CreateNormalizationRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateNormalizationRequest $request)
    {
        $this->authorize('create', Normalization::class);
        $input = $request->all();

        $normalization = $this->normalizationRepository->create($input);

        Flash::success('Normalization saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('normalizations.index'));
        }
    }

    /**
     * Display the specified Normalization.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $normalization = $this->normalizationRepository->getBySlug($slug);

        $this->authorize('view', $normalization);

        if (empty($normalization)) {
            Flash::error('Normalization not found');

            return redirect(route('normalizations.index'));
        }

        return view('lemurbot::normalizations.show')->with(
            ['normalization'=>$normalization, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Normalization.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $normalization = $this->normalizationRepository->getBySlug($slug);

        $this->authorize('update', $normalization);

        if (empty($normalization)) {
            Flash::error('Normalization not found');

            return redirect(route('normalizations.index'));
        }

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::normalizations.edit')->with(
            ['normalization'=> $normalization, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Update the specified Normalization in storage.
     *
     * @param  string $slug
     * @param UpdateNormalizationRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateNormalizationRequest $request)
    {
        $normalization = $this->normalizationRepository->getBySlug($slug);

        $this->authorize('update', $normalization);

        if (empty($normalization)) {
            Flash::error('Normalization not found');

            return redirect(route('normalizations.index'));
        }

        $input = $request->all();

        $normalization = $this->normalizationRepository->update($input, $normalization->id);

        Flash::success('Normalization updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('normalizations.index'));
        }
    }

    /**
     * Remove the specified Normalization from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $normalization = $this->normalizationRepository->getBySlug($slug);

        $this->authorize('delete', $normalization);

        if (empty($normalization)) {
            Flash::error('Normalization not found');

            return redirect(route('normalizations.index'));
        }

        $this->normalizationRepository->delete($normalization->id);

        Flash::success('Normalization deleted successfully.');

        return redirect()->back();
    }
}
