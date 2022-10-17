@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Bot Key
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('lemurbot::bot_keys.show_fields')

                    <!-- Updated At Field -->
                    <div class="form-group">
                        {!! Form::label('updated_at', 'Updated:') !!}
                        <p>{{ $botKey->updated_at }}</p>
                    </div>

                    <!-- Created At Field -->
                    <div class="form-group">
                        {!! Form::label('created_at', 'Created:') !!}
                        <p>{{ $botKey->created_at }}</p>
                    </div>

                    <a href="{{ route('botKeys.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
