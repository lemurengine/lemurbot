<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\EmptyResponseDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateEmptyResponseRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateEmptyResponseRequest;
use LemurEngine\LemurBot\Repositories\EmptyResponseRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\EmptyResponse;

class EmptyResponseController extends AppBaseController
{
    private EmptyResponseRepository $emptyResponseRepository;

    //to help with data testing and form settings
    public string $link = 'emptyResponses';
    public string $htmlTag = 'empty-responses';
    public string $title = 'Empty Responses';
    public string $resourceFolder = 'lemurbot::empty_responses';

    public function __construct(EmptyResponseRepository $emptyResponseRepo)
    {
        $this->emptyResponseRepository = $emptyResponseRepo;
    }

    /**
     * Display a listing of the EmptyResponse.
     *
     * @param EmptyResponseDataTable $emptyResponseDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(EmptyResponseDataTable $emptyResponseDataTable)
    {
        $this->authorize('viewAny', EmptyResponse::class);
        $emptyResponseDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $emptyResponseDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new EmptyResponse.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        return view('lemurbot::empty_responses.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
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
     * Display the specified EmptyResponse.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $emptyResponse = $this->emptyResponseRepository->getBySlug($slug);

        $this->authorize('view', $emptyResponse);

        if (empty($emptyResponse)) {
            Flash::error('Empty Response not found');

            return redirect(route('emptyResponses.index'));
        }

        return view('lemurbot::empty_responses.show')->with(
            ['emptyResponse'=>$emptyResponse, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Generic message page - telling user this action is not allowed
     *
     */
    public function edit()
    {
        //show error message page
        return view('lemurbot::empty_responses.edit')->with(
            [ 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
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
        //we do not store items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified EmptyResponse from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $emptyResponse = $this->emptyResponseRepository->getBySlug($slug);

        $this->authorize('delete', $emptyResponse);

        if (empty($emptyResponse)) {
            Flash::error('Empty Response not found');

            return redirect(route('emptyResponses.index'));
        }

        $this->emptyResponseRepository->delete($emptyResponse->id);

        Flash::success('Empty Response deleted successfully.');

        return redirect()->back();
    }
}
