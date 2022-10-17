<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\MapValueDataTable;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Http\Requests\CreateMapValueRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateMapValueRequest;
use LemurEngine\LemurBot\Http\Requests\UploadMapValueFileRequest;
use LemurEngine\LemurBot\Models\Map;
use LemurEngine\LemurBot\Repositories\MapValueRepository;
use LemurEngine\LemurBot\Services\MapValueUploadService;
use Exception;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\MapValue;

class MapValueController extends AppBaseController
{
    private MapValueRepository $mapValueRepository;

    //to help with data testing and form settings
    public string $link = 'mapValues';
    public string $htmlTag = 'map-values';
    public string $title = 'Map Values';
    public string $resourceFolder = 'lemurbot::map_values';

    public function __construct(MapValueRepository $mapValueRepo)
    {
        $this->middleware('auth');
        $this->mapValueRepository = $mapValueRepo;
    }

    /**
     * Display a listing of the MapValue.
     *
     * @param MapValueDataTable $mapValueDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(MapValueDataTable $mapValueDataTable)
    {
        $this->authorize('viewAny', MapValue::class);
        $mapValueDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $mapValueDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new MapValue.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', MapValue::class);

        $mapList = Map::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::map_values.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'mapList'=>$mapList]
        );
    }

    /**
     * Store a newly created MapValue in storage.
     *
     * @param CreateMapValueRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateMapValueRequest $request)
    {
        $this->authorize('create', MapValue::class);
        $input = $request->all();

        $mapValue = $this->mapValueRepository->create($input);

        Flash::success('Map Value saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('mapValues.index'));
        }
    }

    /**
     * Display the specified MapValue.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $mapValue = $this->mapValueRepository->getBySlug($slug);

        $this->authorize('view', $mapValue);

        if (empty($mapValue)) {
            Flash::error('Map Value not found');

            return redirect(route('mapValues.index'));
        }

        return view('lemurbot::map_values.show')->with(
            ['mapValue'=>$mapValue, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified MapValue.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $mapValue = $this->mapValueRepository->getBySlug($slug);

        $this->authorize('update', $mapValue);

        $mapList = Map::orderBy('name')->pluck('name', 'slug');

        if (empty($mapValue)) {
            Flash::error('Map Value not found');

            return redirect(route('mapValues.index'));
        }

        return view('lemurbot::map_values.edit')->with(
            ['mapValue'=> $mapValue, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'mapList'=>$mapList]
        );
    }

    /**
     * Update the specified MapValue in storage.
     *
     * @param  string $slug
     * @param UpdateMapValueRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateMapValueRequest $request)
    {
        $mapValue = $this->mapValueRepository->getBySlug($slug);

        $this->authorize('update', $mapValue);

        if (empty($mapValue)) {
            Flash::error('Map Value not found');

            return redirect(route('mapValues.index'));
        }

        $input = $request->all();

        $mapValue = $this->mapValueRepository->update($input, $mapValue->id);

        Flash::success('Map Value updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('mapValues.index'));
        }
    }

    /**
     * Remove the specified MapValue from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $mapValue = $this->mapValueRepository->getBySlug($slug);

        $this->authorize('delete', $mapValue);

        if (empty($mapValue)) {
            Flash::error('Map Value not found');

            return redirect(route('mapValues.index'));
        }

        $this->mapValueRepository->delete($mapValue->id);

        Flash::success('Map Value deleted successfully.');

        return redirect()->back();
    }


    /**
     * Show the form for creating a uploading a MapValues file.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function uploadForm()
    {
        $this->authorize('create', MapValue::class);
        return view($this->resourceFolder.'.upload')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for processing an upload form.
     *
     * @param UploadMapValueFileRequest $request
     * @param MapValueUploadService $uploadService
     * @return void
     * @throws AuthorizationException
     */
    public function upload(UploadMapValueFileRequest $request, MapValueUploadService $uploadService)
    {
        $this->authorize('create', MapValue::class);

        //start the transaction
        DB::beginTransaction();

        try {

            $file = $request->file('upload_file');
            $input = $request->input();
            $insertMapValueCount = $uploadService->bulkInsertFromFile($file, $input);
            Flash::success($insertMapValueCount .' Map Values saved successfully.');

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
            return redirect('mapValuesUpload');
        }
    }
}
