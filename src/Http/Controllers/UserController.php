<?php

namespace LemurEngine\LemurBot\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\App;
use LemurEngine\LemurBot\DataTables\UserDataTable;
use LemurEngine\LemurBot\Exceptions\Handler;
use LemurEngine\LemurBot\Facades\LemurPriv;
use Laracasts\Flash\Flash;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use LemurEngine\LemurBot\Http\Requests\CreateUserRequest;
use LemurEngine\LemurBot\Http\Requests\UpdateUserRequest;
use LemurEngine\LemurBot\Models\User;
use LemurEngine\LemurBot\Repositories\UserRepository;

class UserController extends AppBaseController
{
    private UserRepository $userRepository;

    //to help with data testing and form settings
    public string $link = 'users';
    public string $htmlTag = 'users';
    public string $title = 'Users';
    public string $resourceFolder = 'lemurbot::users';

    public function __construct(UserRepository $userRepo)
    {
        $this->middleware('auth');
        $this->userRepository = $userRepo;

        App::singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            Handler::class
        );
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     * @throws AuthorizationException
     */
    public function index(UserDataTable $userDataTable)
    {
        $this->authorize('viewAny', User::class);
        $userDataTable->setDrawParams(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
        return $userDataTable->render(
            $this->resourceFolder.'.index',
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', User::class);
        return view($this->resourceFolder.'.create')->with(
            ['link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function store(CreateUserRequest $request)
    {
        $this->authorize('create', User::class);
        $input = $request->all();

        //start the transaction
        DB::beginTransaction();
        try {

            $user = $this->userRepository->create($input);

            //assign the bot user role
            LemurPriv::assignRole($user->id, $input['user_role']);

            Flash::success('User saved successfully.');
            // Commit the transaction
            DB::commit();
        } catch (Exception $e) {
            // An error occurred; cancel the transaction...
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
            return redirect(route($this->link.'.index'));
        }
    }

    /**
     * Display the specified User.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function show($slug)
    {
        $user = $this->userRepository->getBySlug($slug);
        $this->authorize('view', $user);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route($this->link.'.index'));
        }

        return view($this->resourceFolder.'.show')->with(
            ['user'=>$user, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function edit($slug)
    {
        $user = $this->userRepository->getBySlug($slug);

        $this->authorize('update', $user);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route($this->link.'.index'));
        }


        return view($this->resourceFolder.'.edit')->with(
            ['user'=> $user, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }

    /**
     * Update the specified User in storage.
     *
     * @param  string $slug
     * @param UpdateUserRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function update($slug, UpdateUserRequest $request)
    {

        $user = $this->userRepository->getBySlug($slug);

        $this->authorize('update', $user);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route($this->link.'.index'));
        }

        $input = $request->all();

        //start the transaction
        DB::beginTransaction();

        try {

            $user = $this->userRepository->update($input, $user->id);

            if($user->id != Auth::user()->id){
                //assign the bot user role
                LemurPriv::assignRole($user->id, $input['user_role']);
            }


            Flash::success('User updated successfully.');
            // Commit the transaction
            DB::commit();
        } catch (Exception $e) {
            // An error occurred; cancel the transaction...
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
            return redirect(route($this->link.'.index'));
        }
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  string $slug
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($slug)
    {
        $user = $this->userRepository->getBySlug($slug);

        $this->authorize('delete', $user);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route($this->link.'.index'));
        }

        $this->userRepository->delete($user->id);

        Flash::success('User deleted successfully.');

        return redirect()->back();
    }


    /**
     * Display the specified User.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function profile()
    {
        $user = $this->userRepository->find(Auth::user()->id);
        $this->authorize('view', $user);

        if (empty($user)) {
            return view($this->resourceFolder.'.error')->with(
                ['message'=> "This profile does not exist.", 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
            );
        }

        return view($this->resourceFolder.'.profile')->with(
            ['user'=>$user, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }



    /**
     * Update the specified User in storage.
     *
     * @param  string $slug
     * @param UpdateUserRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function profileUpdate(UpdateUserRequest $request)
    {

        $input = $request->all();

        $user = $this->userRepository->find(Auth::user()->id);
        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route($this->link.'.index'));
        }

        $this->authorize('update', $user);

        if ($user->slug!=$input['user_id']) {
            return view($this->resourceFolder.'.error')->with(
                ['message'=> "You do not have permission to edit this profile.",
                    'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                    'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
            );
        } elseif (!Hash::check($input['password'], $user->password)) {
            Flash::error('Incorrect password');
            return redirect(url('/profile'));
        }

        $user = $this->userRepository->update($input, $user->id);

        Flash::success('Profile updated successfully.');

        if (!empty($input['redirect_url'])) {
            return redirect(url('/profile'));
        } else {
            return redirect(url('/profile'));
        }
    }


    /**
     * Display the specified User.
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function tokens()
    {
        $user = $this->userRepository->find(Auth::user()->id);
        $this->authorize('view', $user);


        if (empty($user)) {
            return view($this->resourceFolder.'.error')->with(
                ['message'=> "This profile does not exist.", 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
            );
        }

        return view($this->resourceFolder.'.tokens')->with(
            ['token'=>false, 'user'=>$user, 'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }



    /**
     * Update the specified User in storage.
     *
     * @param  string $slug
     * @param UpdateUserRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function tokensUpdate(Request $request)
    {
        $user = Auth::user();
        $user = User::where('user_id', $user->id)->first();
        $input = $request->all();


        if ($input['key_type']=='bot_key') {
            //delete any current token of this type for this user
            $user->tokens()->where('name', $input['key_type'])->delete();

            $token = $user->createToken($input['key_type'], ['bot:chat'])->plainTextToken;
        } elseif ($input['key_type']=='account_key') {
            //delete any current token of this type for this user
            $user->tokens()->where('name', $input['key_type'])->delete();
            $token = $user->createToken($input['key_type'], ['account:admin'])->plainTextToken;
        } else {
            Flash::error('Key type not found');
            return redirect(url('/tokens'));
        }


        Flash::success('Token refreshed successfully.');

        return view($this->resourceFolder.'.tokens')->with(
            ['token'=>$token,'key_type'=>$input['key_type'], 'user'=>$user,
                'link'=>$this->link, 'htmlTag'=>$this->htmlTag,
                'title'=>$this->title, 'resourceFolder'=>$this->resourceFolder]
        );
    }


    /**
     * Restore the a soft deleted it...
     *
     * @param User $user
     *
     * @return Response
     * @throws Exception
     */
    public function restore(User $user)
    {

        $this->authorize('restore', $user);


        $this->userRepository->makeModel()->withTrashed()->where('id',$user->id)->first()->restore();

        if (empty($user)) {
            Flash::error('Error restoring user');
            return redirect(route($this->link.'.index'));
        }

        Flash::success('User restored successfully.');

        if(!empty($input['redirect_url'])){
            return redirect($input['redirect_url']);
        }else{
            return redirect(route($this->link.'.index'));
        }

    }


    /**
     * Remove the specified Bot from storage.
     *
     * @param User $user
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function forceDestroy(User $user)
    {

        $this->authorize('forceDelete', $user);

        if (empty($user)) {
            Flash::error('User not found');
            return redirect()->back();
        }


        try {
            $this->userRepository->forceDelete($user->id);
            Flash::success('User permanently deleted successfully.');
        } catch (Exception $e) {

            // An error occurred; cancel the transaction...
            Log::error($e);

            //display generic error
            Flash::error('An error occurred - no changes have been made.<br/>Please check sure that all the bots and bot data belonging to this user has been deleted before you try to delete the user.');
            //if admin display a little more info
            if(LemurPriv::isAdmin(Auth::user()) && (config('lemurbot.portal.show_detailed_error_messages'))){
                Flash::error($e->getMessage());
            }

        }


        return redirect()->back();
    }


    /**
     * Update the specified User in storage.
     *
     * @param  User $user
     * @param UpdateUserSlugRequest $request
     *
     * @return Response
     * @throws AuthorizationException
     */
    public function slugUpdate(User $user, UpdateUserSlugRequest $request)
    {

        $this->authorize('update', $user);

        $inputAll=$request->all();

        $userCheck = $this->userRepository->getBySlug($inputAll['original_slug']);

        if (empty($user)||empty($userCheck)) {
            Flash::error('User not found');
            return redirect(route($this->link.'.index'));
        }

        if($userCheck->id != $user->id){
            Flash::error('User slug mismatch');
            return redirect(route($this->link.'.index'));
        }


        $input['slug'] = $inputAll['slug'];
        $user = $this->userRepository->update($input, $user->id);

        Flash::success('User slug updated successfully.');

        return redirect(route($this->link.'.index'));



    }
}
