<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Illuminate\Support\Facades\App;
use LemurEngine\LemurBot\DataTables\ClientDataTable;
use LemurEngine\LemurBot\Exceptions\Handler;
use LemurEngine\LemurBot\Http\Requests\UpdateClientRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateClientSlugRequest;
use LemurEngine\LemurBot\Repositories\ClientRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Client;

class ClientController extends AppBaseController
{
    private ClientRepository $clientRepository;

    //to help with data testing and form settings
    public string $link = 'clients';
    public string $htmlTag = 'clients';
    public string $title = 'Clients';
    public string $resourceFolder = 'lemurbot::clients';

    public function __construct(ClientRepository $clientRepo)
    {
        $this->middleware('auth');
        $this->clientRepository = $clientRepo;

        App::singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Handler::class
        );
    }

    /**
     * Display a listing of the Client.
     *
     * @param ClientDataTable $clientDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(ClientDataTable $clientDataTable)
    {
        $this->authorize('viewAny', Client::class);
        $clientDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $clientDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Generic message page - telling user this action is not allowed
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {

        return view('lemurbot::clients.create')->with(
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
     * Display the specified Client.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $client = $this->clientRepository->getBySlug($slug);

        $this->authorize('view', $client);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        return view('lemurbot::clients.show')->with(
            ['client'=>$client, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Generic message page - telling user this action is not allowed
     *
     */
    public function edit($slug)
    {
        $client = $this->clientRepository->getBySlug($slug);

        $this->authorize('update', $client);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        //show error message page
        return view('lemurbot::clients.edit')->with(
            [ 'client'=>$client,
                'link'=>$this->link,
            'htmlTag'=>$this->htmlTag,
                'title'=>$this->title,
            'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Client in storage.
     *
     * @param  string $slug
     * @param UpdateClientRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateClientRequest $request)
    {

        $client = $this->clientRepository->getBySlug($slug);

        $this->authorize('update', $client);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        $input = $request->all();

        $client = $this->clientRepository->update($input, $client->id);

        Flash::success('Client updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('clients.index'));
        }
    }

    /**
     * Remove the specified Client from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $client = $this->clientRepository->getBySlug($slug);

        $this->authorize('delete', $client);

        if (empty($client)) {
            Flash::error('Client not found');

            return redirect(route('clients.index'));
        }

        $this->clientRepository->delete($client->id);

        Flash::success('Client deleted successfully.');

        return redirect()->back();
    }

    /**
     * Update the specified Client in storage.
     *
     * @param  Client $client
     * @param UpdateClientSlugRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function slugUpdate($client, UpdateClientSlugRequest $request)
    {

        $this->authorize('update', $client);

        $inputAll=$request->all();

        $clientCheck = $this->clientRepository->getBySlug($inputAll['original_slug']);

        if (empty($client)||empty($clientCheck)) {
            Flash::error('Client not found');
            return redirect(route('clients.index'));
        }

        if($clientCheck->id != $client->id){
            Flash::error('Client slug mismatch');
            return redirect(route('clients.index'));
        }


        $input['slug'] = $inputAll['slug'];
        $client = $this->clientRepository->update($input, $client->id);

        Flash::success('Client slug updated successfully.');

        return redirect(route('clients.index'));



    }
}
