@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Clients
        </h1>
    </section>
    <div class="content">
        @include('lemurbot::layouts.feedback')
        <div class="box box-primary">
            <div class="box-body">
                <div class="callout callout-info col-md-12">You cannot create clients directly, they are automatically created from the clientId sent when a conversation starts.</div>
            </div>
        </div>


    </div>
@endsection
