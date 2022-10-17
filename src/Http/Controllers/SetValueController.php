<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\SetValueDataTable;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Http\Requests\CreateSetValueRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateSetValueRequest;
use LemurEngine\LemurBot\Http\Requests\UploadSetValueFileRequest;
use LemurEngine\LemurBot\Models\Set;
use LemurEngine\LemurBot\Repositories\SetValueRepository;
use LemurEngine\LemurBot\Services\SetValueUploadService;
use Exception;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\SetValue;

class SetValueController extends AppBaseController
{
    private SetValueRepository $setValueRepository;

    //to help with data testing and form settings
    public string $link = 'setValues';
    public string $htmlTag = 'set-values';
    public string $title = 'Set Values';
    public string $resourceFolder = 'lemurbot::set_values';

    public function __construct(SetValueRepository $setValueRepo)
    {
        $this->middleware('auth');
        $this->setValueRepository = $setValueRepo;
    }

    /**
     * Display a listing of the SetValue.
     *
     * @param SetValueDataTable $setValueDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(SetValueDataTable $setValueDataTable)
    {
        $this->authorize('viewAny', SetValue::class);
        $setValueDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $setValueDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new SetValue.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', SetValue::class);

        $setList = Set::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::set_values.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'setList' => $setList]
        );
    }

    /**
     * Store a newly created SetValue in storage.
     *
     * @param CreateSetValueRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateSetValueRequest $request)
    {
        $this->authorize('create', SetValue::class);
        $input = $request->all();

        $setValue = $this->setValueRepository->create($input);

        Flash::success('Set Value saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('setValues.index'));
        }
    }

    /**
     * Display the specified SetValue.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $setValue = $this->setValueRepository->getBySlug($slug);
        $this->authorize('view', $setValue);

        if (empty($setValue)) {
            Flash::error('Set Value not found');

            return redirect(route('setValues.index'));
        }

        return view('lemurbot::set_values.show')->with(
            ['setValue'=>$setValue, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified SetValue.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $setValue = $this->setValueRepository->getBySlug($slug);
        $this->authorize('update', $setValue);

        if (empty($setValue)) {
            Flash::error('Set Value not found');

            return redirect(route('setValues.index'));
        }

        $setList = Set::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::set_values.edit')->with(
            ['setValue'=> $setValue, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'setList' => $setList]
        );
    }

    /**
     * Update the specified SetValue in storage.
     *
     * @param  string $slug
     * @param UpdateSetValueRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateSetValueRequest $request)
    {
        $setValue = $this->setValueRepository->getBySlug($slug);

        $this->authorize('update', $setValue);

        if (empty($setValue)) {
            Flash::error('Set Value not found');

            return redirect(route('setValues.index'));
        }

        $input = $request->all();

        $setValue = $this->setValueRepository->update($input, $setValue->id);

        Flash::success('Set Value updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('setValues.index'));
        }
    }

    /**
     * Remove the specified SetValue from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $setValue = $this->setValueRepository->getBySlug($slug);

        $this->authorize('delete', $setValue);

        if (empty($setValue)) {
            Flash::error('Set Value not found');

            return redirect(route('setValues.index'));
        }

        $this->setValueRepository->delete($setValue->id);

        Flash::success('Set Value deleted successfully.');

        return redirect()->back();
    }


    /**
     * Show the form for creating a uploading a SetValues file.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function uploadForm()
    {
        $this->authorize('create', SetValue::class);
        return view($this->resourceFolder.'.upload')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for processing an upload form.
     *
     * @param UploadSetValueFileRequest $request
     * @param SetValueUploadService $uploadService
     * @return void
     * @throws AuthorizationException
     */
    public function upload(UploadSetValueFileRequest $request, SetValueUploadService $uploadService)
    {
        $this->authorize('create', SetValue::class);

        //start the transaction
        DB::beginTransaction();

        try {

            $file = $request->file('upload_file');
            $input = $request->input();
            $insertSetValueCount = $uploadService->bulkInsertFromFile($file, $input);
            Flash::success($insertSetValueCount .' Set Values saved successfully.');

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
            return redirect('setValuesUpload');
        }
    }
}
