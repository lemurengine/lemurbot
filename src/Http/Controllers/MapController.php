<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\MapDataTable;
use LemurEngine\LemurBot\Http\Requests\CreateMapRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateMapRequest;
use LemurEngine\LemurBot\Repositories\MapRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Map;

class MapController extends AppBaseController
{
    private MapRepository $mapRepository;

    //to help with data testing and form settings
    public string $link = 'maps';
    public string $htmlTag = 'maps';
    public string $title = 'Maps';
    public string $resourceFolder = 'lemurbot::maps';

    public function __construct(MapRepository $mapRepo)
    {
        $this->middleware('auth');
        $this->mapRepository = $mapRepo;
    }

    /**
     * Display a listing of the Map.
     *
     * @param MapDataTable $mapDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(MapDataTable $mapDataTable)
    {
        $this->authorize('viewAny', Map::class);
        $mapDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $mapDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Map.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Map::class);
        return view('lemurbot::maps.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Store a newly created Map in storage.
     *
     * @param CreateMapRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateMapRequest $request)
    {
        $this->authorize('create', Map::class);
        $input = $request->all();

        $map = $this->mapRepository->create($input);

        Flash::success('Map saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('maps.index'));
        }
    }

    /**
     * Display the specified Map.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $map = $this->mapRepository->getBySlug($slug);

        $this->authorize('view', $map);

        if (empty($map)) {
            Flash::error('Map not found');

            return redirect(route('maps.index'));
        }

        return view('lemurbot::maps.show')->with(
            ['map'=>$map, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Map.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $map = $this->mapRepository->getBySlug($slug);

        $this->authorize('update', $map);

        if (empty($map)) {
            Flash::error('Map not found');

            return redirect(route('maps.index'));
        }

        return view('lemurbot::maps.edit')->with(
            ['map'=> $map, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Map in storage.
     *
     * @param  string $slug
     * @param UpdateMapRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateMapRequest $request)
    {
        $map = $this->mapRepository->getBySlug($slug);

        $this->authorize('update', $map);

        if (empty($map)) {
            Flash::error('Map not found');

            return redirect(route('maps.index'));
        }

        $input = $request->all();

        $map = $this->mapRepository->update($input, $map->id);

        Flash::success('Map updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('maps.index'));
        }
    }

    /**
     * Remove the specified Map from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $map = $this->mapRepository->getBySlug($slug);

        $this->authorize('delete', $map);

        if (empty($map)) {
            Flash::error('Map not found');

            return redirect(route('maps.index'));
        }

        $this->mapRepository->delete($map->id);

        Flash::success('Map deleted successfully.');

        return redirect()->back();
    }
}
