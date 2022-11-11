<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Illuminate\Support\Facades\DB;
use LemurEngine\LemurBot\DataTables\WordSpellingDataTable;
use LemurEngine\LemurBot\Exceptions\WordSpellingUploadException;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Http\Requests\CreateWordSpellingRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateWordSpellingRequest;
use LemurEngine\LemurBot\Http\Requests\UploadWordSpellingFileRequest;
use LemurEngine\LemurBot\Models\Category;
use LemurEngine\LemurBot\Models\CategoryGroup;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\WordSpelling;
use LemurEngine\LemurBot\Models\WordSpellingGroup;
use LemurEngine\LemurBot\Repositories\WordSpellingRepository;
use LemurEngine\LemurBot\Services\WordSpellingUploadService;
use Laracasts\Flash\Flash;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class WordSpellingController extends AppBaseController
{
    private WordSpellingRepository $wordSpellingRepository;

    //to help with data testing and form settings
    public string $link = 'wordSpellings';
    public string $htmlTag = 'word-spellings';
    public string $title = 'Word Spellings';
    public string $resourceFolder = 'lemurbot::word_spellings';

    public function __construct(WordSpellingRepository $wordSpellingRepo)
    {
        $this->middleware('auth');
        $this->wordSpellingRepository = $wordSpellingRepo;
    }

    /**
     * Display a listing of the WordSpelling.
     *
     * @param WordSpellingDataTable $wordSpellingDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(WordSpellingDataTable $wordSpellingDataTable)
    {
        $this->authorize('viewAny', WordSpelling::class);
        $wordSpellingDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $wordSpellingDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new WordSpelling.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', WordSpelling::class);

        $wordSpellingGroupList = WordSpellingGroup::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::word_spellings.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'wordSpellingGroupList'=>$wordSpellingGroupList]
        );
    }

    /**
     * Store a newly created WordSpelling in storage.
     *
     * @param CreateWordSpellingRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateWordSpellingRequest $request)
    {
        $this->authorize('create', WordSpelling::class);
        $input = $request->all();

        $wordSpelling = $this->wordSpellingRepository->create($input);

        Flash::success('Word Spelling saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('wordSpellings.index'));
        }
    }

    /**
     * Display the specified WordSpelling.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $wordSpelling = $this->wordSpellingRepository->getBySlug($slug);
        $this->authorize('view', $wordSpelling);

        if (empty($wordSpelling)) {
            Flash::error('Word Spelling not found');

            return redirect(route('wordSpellings.index'));
        }

        return view('lemurbot::word_spellings.show')->with(
            ['wordSpelling'=>$wordSpelling, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified WordSpelling.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $wordSpelling = $this->wordSpellingRepository->getBySlug($slug);

        $this->authorize('update', $wordSpelling);

        if (empty($wordSpelling)) {
            Flash::error('Word Spelling not found');

            return redirect(route('wordSpellings.index'));
        }

        $wordSpellingGroupList = WordSpellingGroup::orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::word_spellings.edit')->with(
            ['wordSpelling'=> $wordSpelling, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder,
            'wordSpellingGroupList'=>$wordSpellingGroupList]
        );
    }

    /**
     * Update the specified WordSpelling in storage.
     *
     * @param  string $slug
     * @param UpdateWordSpellingRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateWordSpellingRequest $request)
    {
        $wordSpelling = $this->wordSpellingRepository->getBySlug($slug);
        $this->authorize('update', $wordSpelling);

        if (empty($wordSpelling)) {
            Flash::error('Word Spelling not found');

            return redirect(route('wordSpellings.index'));
        }

        $input = $request->all();

        $wordSpelling = $this->wordSpellingRepository->update($input, $wordSpelling->id);

        Flash::success('Word Spelling updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('wordSpellings.index'));
        }
    }

    /**
     * Remove the specified WordSpelling from storage.
     *
     * @param string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $wordSpelling = $this->wordSpellingRepository->getBySlug($slug);
        $this->authorize('delete', $wordSpelling);

        if (empty($wordSpelling)) {
            Flash::error('Word Spelling not found');

            return redirect(route('wordSpellings.index'));
        }

        $this->wordSpellingRepository->force($wordSpelling->id);

        Flash::success('Word Spelling deleted successfully.');

        return redirect()->back();
    }

    /**
     * Show the form for creating a uploading a WordSpellings file.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function uploadForm()
    {
        $this->authorize('create', WordSpelling::class);
        return view($this->resourceFolder.'.upload')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for processing an upload form.
     *
     * @param UploadWordSpellingFileRequest $request
     * @param WordSpellingUploadService $uploadService
     * @return void
     * @throws AuthorizationException
     */
    public function upload(UploadWordSpellingFileRequest $request, WordSpellingUploadService $uploadService)
    {
        $this->authorize('create', WordSpelling::class);

        //start the transaction
        DB::beginTransaction();

        try {

            $file = $request->file('upload_file');
            $input = $request->input();
            $insertWordSpellingCount = $uploadService->bulkInsertFromFile($file, $input);
            Flash::success($insertWordSpellingCount . ' Word Spellings saved successfully.');

            // Commit the transaction
            DB::commit();
        } catch (WordSpellingUploadException $e){

            DB::rollback();
            Log::error($e);
            Flash::error($e->getMessage());
            return redirect()->back();

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
            return redirect('wordSpellingsUpload');
        }
    }

    public function download($wordSpellingGroupSlug)
    {
        $wordSpellingGroup = WordSpellingGroup::where('slug', $wordSpellingGroupSlug)->first();
        $language = Language::find($wordSpellingGroup->language_id);
        $languageSlug = $language->slug;
        $wordSpellingsArr = WordSpelling::selectRaw(
            '? as WordSpellingGroupSlug, ? as LanguageSlug, word as Word, replacement as Replacement',
            [$wordSpellingGroupSlug, $languageSlug]
        )->where('word_spelling_group_id', $wordSpellingGroup->id)->orderBy('id')->get()->toArray();

        if (empty($wordSpellingGroup) || count($wordSpellingsArr)<=0) {
            Flash::error('Words not found');
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename='.$wordSpellingGroupSlug.'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        # add headers for each column in the CSV download
        array_unshift($wordSpellingsArr, array_keys($wordSpellingsArr[0]));

        $callback = function () use ($wordSpellingsArr) {
            $FH = fopen('php://output', 'w');
            foreach ($wordSpellingsArr as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }

}
