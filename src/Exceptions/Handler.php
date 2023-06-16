<?php

namespace LemurEngine\LemurBot\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if($exception instanceof ModelNotFoundException){

            if($request->is('bots*')){
                Flash::error('Bot not found');
                return redirect(route('bots.index'));
            }elseif($request->is('bot*')){
                Flash::error('Bot not found');
                return redirect(route('bots.index'));
            }elseif($request->is('category/fromEmptyResponse*')){
                Flash::error('Empty response not found');
                return redirect(route('emptyResponses.index'));
            }elseif($request->is('category/fromTurn*')){
                Flash::error('Turn not found');
                return redirect(route('turns.index'));
            }elseif($request->is('category/fromClientCategory*')){
                Flash::error('Client category not found');
                return redirect(route('clientCategories.index'));
            }elseif($request->is('category/fromMachineLearntCategory*')){
                Flash::error('Machine learnt category not found');
                return redirect(route('machineLearntCategories.index'));
            }elseif($request->is('category*')){
                Flash::error('Category not found');
                return redirect(route('categories.index'));
            }elseif($request->is('*customDocs*')){
                Flash::error('Custom documentation not found');
                return redirect(route('customDocs.index'));
            }elseif($request->is('*conversations*')){
                Flash::error('Conversation not found');
                return redirect(route('conversations.index'));
            }elseif($request->is('*languages*')){
                Flash::error('Language not found');
                return redirect(route('languages.index'));
            }elseif($request->is('*clients*')){
                Flash::error('Client not found');
                return redirect(route('clients.index'));
            }elseif($request->is('*user*')){
                Flash::error('User not found');
                return redirect(route('users.index'));
            }
        }


        return parent::render($request, $exception);
    }
}
