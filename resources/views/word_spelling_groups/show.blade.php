@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Word Spelling Group
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('lemurbot::word_spelling_groups.show_fields')

                    <!-- Updated At Field -->
                    <div class="form-group">
                        {!! Form::label('updated_at', 'Updated:') !!}
                        <p>{{ $wordSpellingGroup->updated_at }}</p>
                    </div>

                    <!-- Created At Field -->
                    <div class="form-group">
                        {!! Form::label('created_at', 'Created:') !!}
                        <p>{{ $wordSpellingGroup->created_at }}</p>
                    </div>

                    <a href="{{ route('wordSpellingGroups.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
