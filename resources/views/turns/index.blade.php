@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Turns</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('lemurbot::layouts.feedback')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body table-responsive">
                    @include('lemurbot::turns.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection
