@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Client Category
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('lemurbot::client_categories.show_fields')

                    <!-- Updated At Field -->
                    <div class="form-group">
                        {!! Form::label('updated_at', 'Updated:') !!}
                        <p>{{ $clientCategory->updated_at }}</p>
                    </div>

                    <!-- Created At Field -->
                    <div class="form-group">
                        {!! Form::label('created_at', 'Created:') !!}
                        <p>{{ $clientCategory->created_at }}</p>
                    </div>

                    <a href="{{ route('clientCategories.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
