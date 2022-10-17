<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\CategoryDataTable;
use LemurEngine\LemurBot\Exceptions\AimlUploadException;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Http\Requests\CreateCategoryRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateCategoryRequest;
use LemurEngine\LemurBot\Http\Requests\UploadCategoryFileRequest;
use LemurEngine\LemurBot\Models\CategoryGroup;
use LemurEngine\LemurBot\Models\ClientCategory;
use LemurEngine\LemurBot\Models\EmptyResponse;
use LemurEngine\LemurBot\Models\Language;
use LemurEngine\LemurBot\Models\MachineLearntCategory;
use LemurEngine\LemurBot\Models\Turn;
use LemurEngine\LemurBot\Repositories\CategoryRepository;
use LemurEngine\LemurBot\Services\AimlUploadService;
use Exception;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Category;
use SimpleXMLElement;

class CategoryController extends AppBaseController
{
    private CategoryRepository $categoryRepository;

    //to help with data testing and form settings
    public string $link = 'categories';
    public string $htmlTag = 'categories';
    public string $title = 'Categories';
    public string $resourceFolder = 'lemurbot::categories';

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->middleware('auth');
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the Category.
     *
     * @param CategoryDataTable $categoryDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(CategoryDataTable $categoryDataTable)
    {
        $this->authorize('viewAny', Category::class);
        $categoryDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $categoryDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Category::class);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::categories.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'categoryGroupList'=>$categoryGroupList]
        );
    }


    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function createFromTurn($id)
    {
        $this->authorize('create', Category::class);

        $turn = Turn::find($id);
        $previousTurn = Turn::PreviousTurn($id);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        if(!isset($categoryGroupList['user-defined-'.Auth::user()->slug])){
            $categoryGroupList['user-defined-'.Auth::user()->slug]='user-defined-'.Auth::user()->slug;
        }


        return view('lemurbot::categories.create_from_turn')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
                'categoryGroupList'=>$categoryGroupList,
                'redirect_url'=>url()->previous(),
                'turn'=>$turn,
                'previousTurn'=>$previousTurn]
        );
    }
    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function createFromEmptyResponse($id)
    {
        $this->authorize('create', Category::class);

        $emptyResponse = EmptyResponse::find($id);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        if(!isset($categoryGroupList['user-defined-'.Auth::user()->slug])){
            $categoryGroupList['user-defined-'.Auth::user()->slug]='user-defined-'.Auth::user()->slug;
        }

        return view('lemurbot::categories.create_from_empty_response')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'categoryGroupList'=>$categoryGroupList,
            'emptyResponse'=>$emptyResponse]
        );
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function createFromClientCategory($id)
    {
        $this->authorize('create', Category::class);

        $clientCategory = ClientCategory::find($id);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        if(!isset($categoryGroupList['user-defined-'.Auth::user()->slug])){
            $categoryGroupList['user-defined-'.Auth::user()->slug]='user-defined-'.Auth::user()->slug;
        }

        return view('lemurbot::categories.create_from_client_category')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
            'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'categoryGroupList'=>$categoryGroupList,
            'clientCategory'=>$clientCategory]
        );
    }


    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function createFromMachineLearntCategory($id)
    {
        $this->authorize('create', Category::class);

        $machineLearntCategory = MachineLearntCategory::find($id);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        if(!isset($categoryGroupList['user-defined-'.Auth::user()->slug])){
            $categoryGroupList['user-defined-'.Auth::user()->slug]='user-defined-'.Auth::user()->slug;
          }

        if(!empty($machineLearntCategory->category_group_slug)){
            if(!isset($categoryGroupList[$machineLearntCategory->category_group_slug])){
                $categoryGroupList[$machineLearntCategory->category_group_slug]=$machineLearntCategory->category_group_slug;
            }
            $defaultCategoryGroup = $machineLearntCategory->category_group_slug;
        }else{
            $defaultCategoryGroup = '';
        }



        return view('lemurbot::categories.create_from_machine_learnt_category')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
                'categoryGroupList'=>$categoryGroupList,
                'defaultCategoryGroup'=>$defaultCategoryGroup,
                'machineLearntCategory'=>$machineLearntCategory]
        );
    }


    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function createFromCopy($id)
    {
        $this->authorize('create', Category::class);

        $category = Category::find($id);

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        if(!isset($categoryGroupList['user-defined-'.Auth::user()->slug])){
            $categoryGroupList['user-defined-'.Auth::user()->slug]='user-defined-'.Auth::user()->slug;
        }

        return view('lemurbot::categories.create_from_copy')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
                'categoryGroupList'=>$categoryGroupList,
                'category'=>$category]
        );
    }
    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateCategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        //start the transaction
        DB::beginTransaction();

        try {

            $input = $request->all();

            $category = $this->categoryRepository->create($input);

            Flash::success('Category saved successfully.');


            //we may need to do some extra deleting
            if (isset($input['delete_original'])) {
                if (isset($input['empty_response_id'])) {
                    EmptyResponse::findAndDeleteFromInput($input);
                } elseif (isset($input['client_category_id'])) {
                    ClientCategory::findAndDeleteFromInput($input);
                } elseif (isset($input['machine_learnt_category_id'])) {
                    MachineLearntCategory::findAndDeleteFromInput($input);
                }
            }

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
            return redirect(route('categories.index'));
        }
    }

    /**
     * Display the specified Category.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $category = $this->categoryRepository->getBySlug($slug);

        $this->authorize('view', $category);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        return view('lemurbot::categories.show')->with(
            ['category'=>$category, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $category = $this->categoryRepository->getBySlug($slug);

        $this->authorize('update', $category);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        $categoryGroupList = CategoryGroup::myEditableItems()->orderBy('name')->pluck('name', 'slug');

        return view('lemurbot::categories.edit')->with(
            ['category'=> $category, 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder,
            'categoryGroupList'=>$categoryGroupList]
        );
    }

    /**
     * Update the specified Category in storage.
     *
     * @param  string $slug
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateCategoryRequest $request)
    {
        $category = $this->categoryRepository->getBySlug($slug);

        $this->authorize('update', $category);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }

        $input = $request->all();

        $category = $this->categoryRepository->update($input, $category->id);


        Flash::success('Category updated successfully.');

        if (!empty($input['action_button']) && $input['action_button']=='Save And Continue') {
            return redirect('/categories/'.$category->slug.'/edit');
        } elseif (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('categories.index'));
        }
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $category = $this->categoryRepository->getBySlug($slug);

        $this->authorize('delete', $category);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('categories.index'));
        }


        $this->categoryRepository->delete($category->id);

        Flash::success('Category deleted successfully.');

        return redirect()->back();
    }

    /**
     * Show the form for creating a uploading a Category file.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function uploadForm()
    {
        $this->authorize('create', Category::class);
        $languageList = Language::orderBy('name')->pluck('name', 'slug');
        return view('lemurbot::categories.upload')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder, 'languageList'=>$languageList]
        );
    }

    /**
     * Show the form for processing an upload form.
     *
     * @param UploadCategoryFileRequest $request
     * @param AimlUploadService $aimlUploadService
     * @return void
     * @throws AuthorizationException
     */
    public function upload(UploadCategoryFileRequest $request, AimlUploadService $aimlUploadService)
    {
        $this->authorize('create', Category::class);

        //start the transaction
        DB::beginTransaction();

        try {

            $file = $request->file('aiml_file');
            $input = $request->input();
            $insertCategoryCount = $aimlUploadService->bulkInsertFromFile($file, $input);
            Flash::success($insertCategoryCount . ' Categories saved successfully.');

            // Commit the transaction
            DB::commit();
        } catch (AimlUploadException $e) {
            DB::rollback();
            Log::error($e);
            //we can display entire message to all - as this will have useful XML parsing errors in it
            Flash::error('An error occurred - no changes have been made - '.$e->getMessage());
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
            return redirect('categoriesUpload');
        }
    }

    public function downloadCsv($categoryGroupSlug)
    {


        $categoryGroup = CategoryGroup::where('slug', $categoryGroupSlug)->first();
        $categoriesArr = Category::selectRaw(
            '? as Filename, pattern as Pattern, topic as Topic, that as That,
                                                template as Template, status as Status',
            [$categoryGroupSlug]
        )->where('category_group_id', $categoryGroup->id)->orderBy('id')->get()->toArray();

        if (empty($categoryGroup) || count($categoriesArr)<=0) {
            Flash::error('Categories not found');
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename='.$categoryGroupSlug.'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];




        # add headers for each column in the CSV download
        array_unshift($categoriesArr, array_keys($categoriesArr[0]));

        $callback = function () use ($categoriesArr) {
            $FH = fopen('php://output', 'w');
            foreach ($categoriesArr as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }



    public function downloadAiml($categoryGroupSlug)
    {


        $categoryGroup = CategoryGroup::where('slug', $categoryGroupSlug)->first();
        $categoriesArr = Category::selectRaw('pattern, topic, that, template, status')
            ->where('category_group_id', $categoryGroup->id)->orderBy('id')->get()->toArray();

        if (empty($categoryGroup) || count($categoriesArr)<=0) {
            Flash::error('Categories not found');
        }

        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/xml'
            ,   'Content-Disposition' => 'attachment; filename='.$categoryGroupSlug.'.aiml'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];


        // creating object of SimpleXMLElement
        $xml = new SimpleXMLElement('<?xml version="1.0"?><aiml></aiml>');





        foreach ($categoriesArr as $index => $category) {

            if ($category['topic']!='') {
                $subNode = $xml->addChild('topic');
                $subNode->addAttribute('name', $category['topic']);
                $subNode = $subNode->addChild('category');
            } else {
                $subNode = $xml->addChild('category');
            }
            $subNode->addChild('pattern', $category['pattern']);
            if ($category['that']!='') {
                $subNode->addChild('that', $category['that']);
            }
            $subNode->addChild('template', $category['template']);
        }

        $stringXml = $xml->asXML();

        //lets try and make it look slightly readable
        $stringXml = str_replace("&gt;", ">", $stringXml);
        $stringXml = str_replace("&lt;", "<", $stringXml);

        $stringXml = str_replace("<aiml", "\n<aiml", $stringXml);
        $stringXml = str_replace("</aiml", "\n</aiml", $stringXml);
        $stringXml = str_replace("<topic", "\n\t<topic", $stringXml);
        $stringXml = str_replace("<category", "\n\t\t<category", $stringXml);
        $stringXml = str_replace("<pattern", "\n\t\t\t<pattern", $stringXml);
        $stringXml = str_replace("<template", "\n\t\t\t<template", $stringXml);
        $stringXml = str_replace("</category", "\n\t\t</category", $stringXml);
        $stringXml = str_replace("</topic", "\n\t</topic", $stringXml);


        $aiml = <<<XML
$stringXml
XML;
        $aiml = simplexml_load_string($aiml);

        return response::make($aiml->asXML(), 200, $headers);
    }

}
