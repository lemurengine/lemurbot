<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Illuminate\Support\Facades\App;
use LemurEngine\LemurBot\DataTables\ConversationDataTable;
use LemurEngine\LemurBot\Exceptions\Handler;
use LemurEngine\LemurBot\Http\Requests\UpdateConversationSlugRequest;
use LemurEngine\LemurBot\Models\Turn;
use LemurEngine\LemurBot\Repositories\ConversationRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Conversation;

class ConversationController extends AppBaseController
{
    private ConversationRepository $conversationRepository;

    //to help with data testing and form settings
    public string $link = 'conversations';
    public string $htmlTag = 'conversations';
    public string $title = 'Conversations';
    public string $resourceFolder = 'lemurbot::conversations';

    public function __construct(ConversationRepository $conversationRepo)
    {
        $this->middleware('auth');
        $this->conversationRepository = $conversationRepo;

        App::singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Handler::class
        );
    }

    /**
     * Display a listing of the Conversation.
     *
     * @param ConversationDataTable $conversationDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(ConversationDataTable $conversationDataTable)
    {
        $this->authorize('viewAny', Conversation::class);
        $conversationDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $conversationDataTable->render(
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
     * Display the specified Conversation.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $conversation = $this->conversationRepository->getBySlug($slug);

        $this->authorize('view', $conversation);

        if (empty($conversation)) {
            Flash::error('Conversation not found');

            return redirect(route('conversations.index'));
        }

        return view('lemurbot::conversations.show')->with(
            ['conversation'=>$conversation, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Generic message page - telling user this action is not allowed
     * @return Response
     */
    public function edit()
    {
        //show error message page
        return view('lemurbot::conversations.edit')->with(
            [ 'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Conversation in storage.
     */
    public function update()
    {
        //we do not store items directly
        abort(403, 'Unauthorized action.');
    }

    /**
     * Remove the specified Conversation from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $conversation = $this->conversationRepository->getBySlug($slug);

        $this->authorize('delete', $conversation);

        if (empty($conversation)) {
            Flash::error('Conversation not found');

            return redirect(route('conversations.index'));
        }

        $this->conversationRepository->delete($conversation->id);

        Flash::success('Conversation deleted successfully.');

        return redirect()->back();
    }

    /**
     * Update the specified Conversation in storage.
     *
     * @param  Conversation $conversation
     * @param UpdateConversationSlugRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function slugUpdate($conversation, UpdateConversationSlugRequest $request)
    {

        $this->authorize('update', $conversation);

        $inputAll=$request->all();

        $conversationCheck = $this->conversationRepository->getBySlug($inputAll['original_slug']);

        if (empty($conversation)||empty($conversationCheck)) {
            Flash::error('Conversation not found');
            return redirect(route('conversations.index'));
        }

        if($conversationCheck->id != $conversation->id){
            Flash::error('Conversation slug mismatch');
            return redirect(route('conversations.index'));
        }


        $input['slug'] = $inputAll['slug'];
        $conversation = $this->conversationRepository->update($input, $conversation->id);

        Flash::success('Conversation slug updated successfully.');

        return redirect(route('conversations.index'));



    }

    public function downloadCsv($botId, $conversationSlug)
    {

        $conversation = Conversation::where('slug', $conversationSlug)->where('bot_id', $botId)->first();
        $turnsArr = Turn::select(['input','output'])->where('conversation_id', $conversation->id)
            ->where('source', 'human')->latest('id')->get()->toArray();


        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename='.$conversation->slug.'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];




        # add headers for each column in the CSV download
        array_unshift($turnsArr, array_keys($turnsArr[0]));

        $callback = function () use ($turnsArr) {
            $FH = fopen('php://output', 'w');
            foreach ($turnsArr as $row) {
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return response()->stream($callback, 200, $headers);
    }
}
