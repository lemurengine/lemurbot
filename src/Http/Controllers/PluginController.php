<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use LemurEngine\LemurBot\DataTables\PluginDataTable;
use LemurEngine\LemurBot\Http\Requests\CreatePluginRequest;
use LemurEngine\LemurBot\Http\Requests\UpdatePluginRequest;
use LemurEngine\LemurBot\Repositories\PluginRepository;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Models\Plugin;

class PluginController extends AppBaseController
{
    private PluginRepository $pluginRepository;

    //to help with data testing and form settings
    public string $link = 'plugins';
    public string $htmlTag = 'plugins';
    public string $title = 'Plugins';
    public string $resourceFolder = 'lemurbot::plugins';

    public function __construct(PluginRepository $pluginRepo)
    {
        $this->middleware('auth');
        $this->pluginRepository = $pluginRepo;
    }

    /**
     * Display a listing of the Plugin.
     *
     * @param PluginDataTable $pluginDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(PluginDataTable $pluginDataTable)
    {
        $this->authorize('viewAny', Plugin::class);
        $pluginDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $pluginDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new Plugin.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Plugin::class);
        return view('lemurbot::plugins.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Store a newly created Plugin in storage.
     *
     * @param CreatePluginRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreatePluginRequest $request)
    {
        $this->authorize('create', Plugin::class);
        $input = $request->all();

        $plugin = $this->pluginRepository->create($input);

        Flash::success('Plugin saved successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('plugins.index'));
        }
    }

    /**
     * Display the specified Plugin.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $plugin = $this->pluginRepository->getBySlug($slug);

        $this->authorize('view', $plugin);

        if (empty($plugin)) {
            Flash::error('Plugin not found');

            return redirect(route('plugins.index'));
        }

        return view('lemurbot::plugins.show')->with(
            ['plugin'=>$plugin, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified Plugin.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $plugin = $this->pluginRepository->getBySlug($slug);

        $this->authorize('update', $plugin);

        if (empty($plugin)) {
            Flash::error('Plugin not found');

            return redirect(route('plugins.index'));
        }

        return view('lemurbot::plugins.edit')->with(
            ['plugin'=> $plugin, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified Plugin in storage.
     *
     * @param  string $slug
     * @param UpdatePluginRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdatePluginRequest $request)
    {
        $plugin = $this->pluginRepository->getBySlug($slug);

        $this->authorize('update', $plugin);

        if (empty($plugin)) {
            Flash::error('Plugin not found');

            return redirect(route('plugins.index'));
        }

        $input = $request->all();

        $plugin = $this->pluginRepository->update($input, $plugin->id);

        Flash::success('Plugin updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect($input['redirect_url']);
        } else {
            return redirect(route('plugins.index'));
        }
    }

    /**
     * Remove the specified Plugin from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $plugin = $this->pluginRepository->getBySlug($slug);

        $this->authorize('delete', $plugin);

        if (empty($plugin)) {
            Flash::error('Plugin not found');

            return redirect(route('plugins.index'));
        }

        $this->pluginRepository->delete($plugin->id);

        Flash::success('Plugin deleted successfully.');

        return redirect()->back();
    }
}
