<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use LemurEngine\LemurBot\DataTables\BotDataTable;
use LemurEngine\LemurBot\Facades\LemurPriv;
use LemurEngine\LemurBot\Http\Requests\CreateTalkRequest;
use LemurEngine\LemurBot\Models\Category;
use LemurEngine\LemurBot\Repositories\BotRepository;
use LemurEngine\LemurBot\Services\TalkService;
use Exception;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Auth\Access\Response as AuthResponse;

class TestController extends AppBaseController
{

    //to help with data testing and form settings
    public string $link = 'tests';
    public string $htmlTag = 'test';
    public string $title = 'Tests';
    public string $resourceFolder = 'lemurbot::test';

    /** @var  BotRepository */
    private $botRepository;

    public function __construct(BotRepository $botRepo)
    {
        $this->middleware('auth');
        $this->botRepository = $botRepo;
    }

    /**
     * Display a listing of the Bot.
     *
     * @param BotDataTable $botDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index()
    {
        //if the user is not an admin do not allow access
        if (!LemurPriv::isAdmin(Auth::user())) {
            return AuthResponse::deny();
        }

        // get a nice list of word spelling groups
        return view('lemurbot::tests.run')->with(
            [
                'link'=>$this->link,
                'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
                'resourceFolder'=>$this->resourceFolder,
            ]
        );

    }




    /**
     * Initiate a talk to the bot...
     * @param CreateTalkRequest $request
     * @param TalkService $talkService
     * @return Factory|View
     * @throws Exception
     */
    public function run(TalkService $talkService)
    {
        //if the user is not an admin do not allow access
        if (!LemurPriv::isAdmin(Auth::user())) {
            return AuthResponse::deny();
        }


        $bot = $this->botRepository->find(1);

        if (empty($bot)) {
            Flash::error('Bot not found');
            return redirect(route('bots.index'));
        }

        $results = [];
        $testCount = 0;
        $successCount = 0;
        $failCount = 0;
        $unknownCount = 0;

        try {

            $categories = Category::join('category_groups', 'category_groups.id','=','categories.category_group_id')
                ->where('category_groups.slug','dev-testcases')
                ->orderBy('categories.id','asc')
                ->pluck('pattern','categories.id');

            $input["client"] = Str::uuid()->toString();
            $input["bot"] = $bot->slug;
            $input["html"] = 1;
            $input["redirect_url"] = url('/test');




            foreach($categories as $id=> $category){

                $originalInput = $category;

                $category = str_replace("*","Test passed",$category);
                $category = str_replace("_","Test passed",$category);

                $input["message"] = $category;
                $res = [];
                $res['error'] = [];

                try {

                    $parts = $talkService->run($input, true);
                    $res['id'] = $id;
                    $res['input'] = $originalInput;
                    $res['output'] = $parts['response']['conversation']['output'];

                    if(stripos($res['output'], 'test passed if')!==false){
                        $res['result']['label'] = 'warning';
                        $res['result']['outcome'] = 'manual inspection';
                        $unknownCount++;
                    }elseif(stripos($res['output'], 'test pass')!==false){
                        $res['result']['label'] = 'success';
                        $res['result']['outcome'] = 'pass';
                        $successCount++;
                    }elseif(stripos($res['output'], 'test fail')!==false){
                        $res['result']['label'] = 'danger';
                        $res['result']['outcome'] = 'fail';
                        $failCount++;
                    }elseif(stripos($res['output'], 'setup')!==false){
                        $res['result']['label'] = 'success';
                        $res['result']['outcome'] = 'pass';
                        $successCount++;
                    }elseif($res['output']=='I don\'t have a response for that'){
                        $res['result']['label'] = 'danger';
                        $res['result']['outcome'] = 'fail';
                        $failCount++;
                    }
                    else{
                        $res['result']['label'] = 'warning';
                        $res['result']['outcome'] = 'unknown';
                        $unknownCount++;
                    }
                    $results[] = $res;
                    $testCount++;

                }catch(Exception $e){
                    $res = [];
                    $res['id'] = $id;
                    $res['input'] = $category;
                    $res['output'] = '';
                    $res['result']['label'] = 'danger';
                    $res['result']['outcome'] = 'fail';
                    $res['error'] = ['message'=>$e->getMessage(),'line'=>$e->getLine(),'file'=>basename($e->getFile())];
                    $results[] = $res;
                    $testCount++;
                    $failCount++;
                }
            }


            // get a nice list of word spelling groups
            return view('lemurbot::tests.results')->with(
                ['bot'=> $bot,
                    'link'=>$this->link,
                    'htmlTag'=>$this->htmlTag,
                    'title'=>$this->title,
                    'resourceFolder'=>$this->resourceFolder,
                    'results'=>$results,
                    'testCount'=>$testCount,
                    'successCount'=>$successCount,
                    'failCount'=>$failCount,
                    'unknownCount'=>$unknownCount,
                    'error'=>false,
                ]
            );
        } catch (Exception $e) {
            // get a nice list of word spelling groups
            return view('lemurbot::tests.results')->with(
                ['bot'=> $bot,
                    'link'=>$this->link,
                    'htmlTag'=>$this->htmlTag,
                    'title'=>$this->title,
                    'resourceFolder'=>$this->resourceFolder,
                    'error'=>true,
                    'testCount'=>$testCount,
                    'successCount'=>$successCount,
                    'failCount'=>$failCount,
                    'unknownCount'=>$unknownCount,
                    'results'=>['error'=>true,'message'=>$e->getMessage(),'line'=>$e->getLine(),'file'=>basename($e->getFile())],
                ]
            );
        }


    }



}
