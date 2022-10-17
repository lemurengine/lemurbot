<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\WordTransformationDataTable;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Http\Requests\CreateWordTransformationRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateWordTransformationRequest;
use LemurEngine\LemurBot\Http\Requests\UploadWordTransformationFileRequest;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Repositories\WordTransformationRepository;
use LemurEngine\LemurBot\Services\WordTransformationUploadService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\WordTransformation;

class WordTransformationController extends AppBaseController
{
    private WordTransformationRepository $wordTransformationRepository;

    //to help with data testing and form settings
    public string $link = 'wordTransformations';
    public string $htmlTag = 'word-transformations';
    public string $title = 'Word Transformations';
    public string $resourceFolder = 'lemurbot::word_transformations';

    public function __construct(WordTransformationRepository $wordTransformationRepo)
    {
        $this->middleware('auth');
        $this->wordTransformationRepository = $wordTransformationRepo;
    }

    /**
     * Display a listing of the WordTransformation.
     *
     * @param WordTransformationDataTable $wordTransformationDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(WordTransformationDataTable $wordTransformationDataTable)
    {
        $this->authorize('viewAny', WordTransformation::class);
        $wordTransformationDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $wordTransformationDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new WordTransformation.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', WordTransformation::class);

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::word_transformations.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Store a newly created WordTransformation in storage.
     *
     * @param CreateWordTransformationRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateWordTransformationRequest $request)
    {
        $this->authorize('create', WordTransformation::class);
        $input = $request->all();

        $wordTransformation = $this->wordTransformationRepository->create($input);

        Flash::success('Word Transformation saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('wordTransformations.index'));
        }
    }

    /**
     * Display the specified WordTransformation.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $wordTransformation = $this->wordTransformationRepository->getBySlug($slug);
        $this->authorize('view', $wordTransformation);

        if (empty($wordTransformation)) {
            Flash::error('Word Transformation not found');

            return redirect(route('wordTransformations.index'));
        }

        return view('lemurbot::word_transformations.show')->with(
            ['wordTransformation'=>$wordTransformation, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified WordTransformation.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $wordTransformation = $this->wordTransformationRepository->getBySlug($slug);

        $this->authorize('update', $wordTransformation);

        if (empty($wordTransformation)) {
            Flash::error('Word Transformation not found');

            return redirect(route('wordTransformations.index'));
        }

        $languageList = Language::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::word_transformations.edit')->with(
            ['wordTransformation'=> $wordTransformation, '
        link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'languageList'=>$languageList]
        );
    }

    /**
     * Update the specified WordTransformation in storage.
     *
     * @param  string $slug
     * @param UpdateWordTransformationRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateWordTransformationRequest $request)
    {
        $wordTransformation = $this->wordTransformationRepository->getBySlug($slug);

        $this->authorize('update', $wordTransformation);

        if (empty($wordTransformation)) {
            Flash::error('Word Transformation not found');

            return redirect(route('wordTransformations.index'));
        }

        $input = $request->all();

        $wordTransformation = $this->wordTransformationRepository->update($input, $wordTransformation->id);

        Flash::success('Word Transformation updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('wordTransformations.index'));
        }
    }

    /**
     * Remove the specified WordTransformation from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $wordTransformation = $this->wordTransformationRepository->getBySlug($slug);

        $this->authorize('delete', $wordTransformation);

        if (empty($wordTransformation)) {
            Flash::error('Word Transformation not found');

            return redirect(route('wordTransformations.index'));
        }

        $this->wordTransformationRepository->delete($wordTransformation->id);

        Flash::success('Word Transformation deleted successfully.');

        return redirect()->back();
    }

    /**
     * Show the form for creating a uploading a WordTransformations file.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function uploadForm()
    {
        $this->authorize('create', WordTransformation::class);
        return view($this->resourceFolder.'.upload')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for processing an upload form.
     *
     * @param UploadWordTransformationFileRequest $request
     * @param WordTransformationUploadService $uploadService
     * @return void
     * @throws AuthorizationException
     */
    public function upload(UploadWordTransformationFileRequest $request, WordTransformationUploadService $uploadService)
    {
        $this->authorize('create', WordTransformation::class);

        //start the transaction
        DB::beginTransaction();

        try {

            $file = $request->file('upload_file');
            $input = $request->input();
            $insertWordTransformationCount = $uploadService->bulkInsertFromFile($file, $input);
            Flash::success($insertWordTransformationCount . ' Word Transformations saved successfully.');

            // Commit the transaction
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e);

            //display generic error
            Flash::error('An error occurred - no changes have been made');
            //if admin display a little more info
            if(LemurPriv::isAdmin(Auth::user()) && (config('lemurbot.portal.show_detailed_error_messages'))){
                Flash::error($e->getMessage());
            }

            return redirect()->back();
        }

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect('wordTransformationsUpload');
        }
    }
}
