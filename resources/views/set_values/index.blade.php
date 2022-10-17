@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Set Values</h1>
        <h1 class="pull-right">
           <a class="btn btn-sm btn-primary pull-right" style="margin-top: -5px;margin-bottom: 0px" href="{{ route('setValues.create') }}">Add New</a>
        </h1>
        <div class="clearfix"></div>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('lemurbot::layouts.feedback')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body table-responsive">
                    @include('lemurbot::set_values.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection
