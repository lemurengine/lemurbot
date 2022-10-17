<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\SectionDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateSectionRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateSectionRequest;
use LemurEngine\LemurBot\Repositories\SectionRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Section;

class SectionController extends AppBaseController
{
    private SectionRepository $sectionRepository;

    //to help with data testing and form settings
    public string $link = 'sections';
    public string $htmlTag = 'sections';
    public string $title = 'Sections';
    public string $resourceFolder = 'lemurbot::sections';

    public function __construct(SectionRepository $sectionRepo)
    {
        $this->middleware('auth');
        $this->sectionRepository = $sectionRepo;
    }

    /**
     * Display a listing of the Section.
     *
     * @param SectionDataTable $sectionDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(SectionDataTable $sectionDataTable)
    {
        $this->authorize('viewAny', Section::class);
        $sectionDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $sectionDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Section.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Section::class);
        return view('lemurbot::sections.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Store a newly created Section in storage.
     *
     * @param CreateSectionRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateSectionRequest $request)
    {
        $this->authorize('create', Section::class);
        $input = $request->all();

        $section = $this->sectionRepository->create($input);

        Flash::success('Section saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('sections.index'));
        }
    }

    /**
     * Display the specified Section.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $section = $this->sectionRepository->getBySlug($slug);

        $this->authorize('view', $section);

        if (empty($section)) {
            Flash::error('Section not found');

            return redirect(route('sections.index'));
        }

        return view('lemurbot::sections.show')->with(
            ['section'=>$section, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Section.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $section = $this->sectionRepository->getBySlug($slug);

        $this->authorize('update', $section);

        if (empty($section)) {
            Flash::error('Section not found');

            return redirect(route('sections.index'));
        }

        return view('lemurbot::sections.edit')->with(
            ['section'=> $section, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Section in storage.
     *
     * @param  string $slug
     * @param UpdateSectionRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateSectionRequest $request)
    {
        $section = $this->sectionRepository->getBySlug($slug);

        $this->authorize('update', $section);

        if (empty($section)) {
            Flash::error('Section not found');

            return redirect(route('sections.index'));
        }

        $input = $request->all();

        $section = $this->sectionRepository->update($input, $section->id);

        Flash::success('Section updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('sections.index'));
        }
    }

    /**
     * Remove the specified Section from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $section = $this->sectionRepository->getBySlug($slug);

        $this->authorize('delete', $section);

        if (empty($section)) {
            Flash::error('Section not found');

            return redirect(route('sections.index'));
        }

        $this->sectionRepository->delete($section->id);

        Flash::success('Section deleted successfully.');

        return redirect()->back();
    }
}
