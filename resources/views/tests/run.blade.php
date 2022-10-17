@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tests</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('lemurbot::layouts.feedback')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body col-md-12">
                <div class="callout callout-warning">Press Start Test to run all the tests contained in the dev-testcases.aiml file.
                <br/>Tests can take upto a minute to run.
                </div>
            </div>
            <div class="col-md-12">
                <a class="btn btn-success" href="{!! url('/test/run') !!}">Start Tests</a>
            </div>
            <div class="clearfix"></div>
            <br/>

        </div>


    </div>
@endsection
