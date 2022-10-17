@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Wildcard
        </h1>
    </section>
    <div class="content">
        @include('lemurbot::layouts.feedback')
        <div class="box box-primary">
            <div class="box-body">
                <div class="callout callout-info col-md-12">You cannot create ratings they are given by clients during a conversation.</div>
            </div>
        </div>


    </div>
@endsection
