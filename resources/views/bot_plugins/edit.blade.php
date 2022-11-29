@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{$title}}}
        </h1>
    </section>

    <div class="content">
        @include('lemurbot::layouts.feedback')
        <div class="box box-primary">
            <div class="box-body edit-page">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group col-sm-12" data-test="no_edit_div">
                            <div class="alert alert-info">
                                You cannot change this relationship. If you need to update it then please just delete the existing relationship and create a new one.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
