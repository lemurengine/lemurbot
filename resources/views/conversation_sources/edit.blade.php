@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Conversation Sources
        </h1>
    </section>
    <div class="content">
        @include('lemurbot::layouts.feedback')
        <div class="box box-primary">
            <div class="box-body">
                <div class="callout callout-info col-md-12">You cannot edit conversation sources they are identified and generated during a conversation between the bot and the user.</div>
            </div>
        </div>


    </div>
@endsection
