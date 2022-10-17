<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\TurnDataTable;
use LemurEngine\LemurBot\Repositories\TurnRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Turn;

class TurnController extends AppBaseController
{
    private TurnRepository $turnRepository;

    //to help with data testing and form settings
    public string $link = 'turns';
    public string $htmlTag = 'turns';
    public string $title = 'Turns';
    public string $resourceFolder = 'lemurbot::turns';

    public function __construct(TurnRepository $turnRepo)
    {
        $this->middleware('auth');
        $this->turnRepository = $turnRepo;
    }

    /**
     * Display a listing of the Turn.
     *
     * @param TurnDataTable $turnDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(TurnDataTable $turnDataTable)
    {
        $this->authorize('viewAny', Turn::class);
        $turnDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $turnDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Generic message page - telling user this action is not allowed
     *
     * @return Response
     */
    public function create()
    {

        return view($this->resourceFolder.'.create')->with(
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
     * Display the specified Turn.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $turn = $this->turnRepository->getBySlug($slug);

        $this->authorize('view', $turn);

        if (empty($turn)) {
            Flash::error('Turn not found');

            return redirect(route('turns.index'));
        }

        return view('lemurbot::turns.show')->with(
            ['turn'=>$turn, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
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
        return view('lemurbot::turns.edit')->with(
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
     * Remove the specified Turn from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $turn = $this->turnRepository->getBySlug($slug);

        $this->authorize('delete', $turn);

        if (empty($turn)) {
            Flash::error('Turn not found');

            return redirect(route('turns.index'));
        }

        $this->turnRepository->delete($turn->id);

        Flash::success('Turn deleted successfully.');

        return redirect()->back();
    }
}
