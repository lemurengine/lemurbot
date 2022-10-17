@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Client
        </h1>
    </section>

    <div class="content">
        @include('lemurbot::layouts.feedback')
        <div class="box box-primary">
            <div class="box-body edit-page">
                <div class="row">
                    <div class="col-md-12">
                    {!! Form::model($client, ['route' => ['clients.update', $client->slug], 'method' => 'patch', 'data-test'=>$htmlTag.'-edit-form', 'class'=>'validate', 'name'=>$htmlTag.'-edit']) !!}

                    @include('lemurbot::clients.fields')

                    <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a href="{{ route('clients.index') }}" class="btn btn-default">Cancel</a>
                        </div>


                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Slug Field Edit Modal -->
    @include('lemurbot::layouts.edit_slug_modal')
@endsection
@push('scripts')
    {{ Html::script('vendor/lemurbot/js/validation.js') }}
    {{ Html::script('vendor/lemurbot/js/select2.js') }}
@endpush
