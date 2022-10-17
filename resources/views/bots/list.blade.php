@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Bots</h1>
        <h1 class="pull-right">
           <a class="btn btn-sm btn-primary pull-right" style="margin-top: -5px;margin-bottom: 0px" href="{{ route('bots.create') }}">Add New</a>
        </h1>
        <div class="clearfix"></div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('lemurbot::layouts.feedback')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body table-responsive">
                   @foreach($bots as $bot)

                    <div class="col-md-2">

                        <!-- Profile Image -->
                        <div class="box">
                            <div class="box-body box-profile">
                                <img class="profile-user-img img-responsive img-circle pull-left" src="{!! $bot->imageUrl !!}" alt="Bot avatar">

                                <h3 class="profile-username text-center">{!! $bot->name !!}</h3>

                                <p class="text-muted text-center">{!! $bot->description !!}</p>

                                <div class="btn-group btn-group-sm bot-commands" role="group" aria-label="Bot Commands">
                                    <a href="{!! url('/chat/'.$bot->slug) !!}" class="btn btn-primary">Chat</a>
                                    @if($bot->user_id == Auth::user()->id )
                                        <a href="{!! url('/bots/'.$bot->slug.'/edit') !!}" class="btn btn-sm btn-info">Edit</a>
                                    @endif
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>


                   @endforeach
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection
