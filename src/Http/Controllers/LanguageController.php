<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Exception;
use LemurEngine\LemurBot\DataTables\LanguageDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateLanguageRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateLanguageRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateLanguageSlugRequest;
use LemurEngine\LemurBot\Repositories\LanguageRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Language;

class LanguageController extends AppBaseController
{
    private LanguageRepository $languageRepository;

    //to help with data testing and form settings
    public string $link = 'languages';
    public string $htmlTag = 'languages';
    public string $title = 'Languages';
    public string $resourceFolder = 'lemurbot::languages';

    public function __construct(LanguageRepository $languageRepo)
    {
        $this->middleware('auth');
        $this->languageRepository = $languageRepo;
    }

    /**
     * Display a listing of the Language.
     *
     * @param LanguageDataTable $languageDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(LanguageDataTable $languageDataTable)
    {
        $this->authorize('viewAny', Language::class);
        $languageDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $languageDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Language.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Language::class);

        return view('lemurbot::languages.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Store a newly created Language in storage.
     *
     * @param CreateLanguageRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateLanguageRequest $request)
    {
        $this->authorize('create', Language::class);

        $input = $request->all();

        $language = $this->languageRepository->create($input);

        Flash::success('Language saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('languages.index'));
        }
    }

    /**
     * Display the specified Language.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $language = $this->languageRepository->getBySlug($slug);

        $this->authorize('view', $language);

        if (empty($language)) {
            Flash::error('Language not found');

            return redirect(route('languages.index'));
        }

        return view('lemurbot::languages.show')->with(
            ['language'=>$language, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Language.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $language = $this->languageRepository->getBySlug($slug);

        $this->authorize('update', $language);

        if (empty($language)) {
            Flash::error('Language not found');

            return redirect(route('languages.index'));
        }

        return view('lemurbot::languages.edit')->with(
            ['language'=> $language, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Language in storage.
     *
     * @param string $slug
     * @param UpdateLanguageRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateLanguageRequest $request)
    {
        $language = $this->languageRepository->getBySlug($slug);

        $this->authorize('update', $language);

        if (empty($language)) {
            Flash::error('Language not found');

            return redirect(route('languages.index'));
        }

        $input = $request->all();

        $language = $this->languageRepository->update($input, $language->id);

        Flash::success('Language updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('languages.index'));
        }
    }

    /**
     * Remove the specified Language from storage.
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
        $language = $this->languageRepository->getBySlug($slug);

        $this->authorize('delete', $language);

        if (empty($language)) {
            Flash::error('Language not found');

            return redirect(route('languages.index'));
        }

        $this->languageRepository->delete($language->id);

        Flash::success('Language deleted successfully.');

        return redirect()->back();
    }

    /**
     * Update the specified Language in storage.
     *
     * @param  Language $language
     * @param UpdateLanguageSlugRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function slugUpdate($language, UpdateLanguageSlugRequest $request)
    {

        $this->authorize('update', $language);

        $inputAll=$request->all();

        $languageCheck = $this->languageRepository->getBySlug($inputAll['original_slug']);

        if (empty($language)||empty($languageCheck)) {
            Flash::error('Language not found');
            return redirect(route('languages.index'));
        }

        if($languageCheck->id != $language->id){
            Flash::error('Language slug mismatch');
            return redirect(route('languages.index'));
        }


        $input['slug'] = $inputAll['slug'];
        $language = $this->languageRepository->update($input, $language->id);

        Flash::success('Language slug updated successfully.');

        return redirect(route('languages.index'));



    }
}
