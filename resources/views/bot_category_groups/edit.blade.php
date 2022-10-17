@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Bot Category Group
        </h1>
    </section>
    <div class="content">
        @include('lemurbot::layouts.feedback')
        <div class="box box-primary">
            <div class="box-body">
                <div class="callout callout-info col-md-12">You cannot edit bot category groups. If you want to update a link between a bot and a category group you can delete/create them.</div>
            </div>
        </div>


    </div>
@endsection
