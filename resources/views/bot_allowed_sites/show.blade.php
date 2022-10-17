@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Bot Allowed Sites
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('lemurbot::bot_allowed_sites.show_fields')

                    <!-- Updated At Field -->
                    <div class="form-group">
                        {!! Form::label('updated_at', 'Updated:') !!}
                        <p>{{ $botAllowedSite->updated_at }}</p>
                    </div>

                    <!-- Created At Field -->
                    <div class="form-group">
                        {!! Form::label('created_at', 'Created:') !!}
                        <p>{{ $botAllowedSite->created_at }}</p>
                    </div>

                    <a href="{{ route('botAllowedSites.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
