@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Users</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('lemurbot::layouts.feedback')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                 <div class="alert alert-danger">{!! $message !!}</div>
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection
