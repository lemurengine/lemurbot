@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Machine Learnt Categories
        </h1>
    </section>
    <div class="content">
        @include('lemurbot::layouts.feedback')
        <div class="box box-primary">
            <div class="box-body">
                <div class="callout callout-info col-md-12">You cannot create machine learnt categories these are records which are taught to the bot by the user during a conversation between the bot and the user.</div>
            </div>
        </div>


    </div>
@endsection
