@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Normalization
        </h1>
   </section>
    <div class="content">
        @include('lemurbot::layouts.feedback')
        <div class="box box-primary">
            <div class="box-body edit-page">
                <div class="row">
                    <div class="col-md-12">
                       {!! Form::model($normalization, ['route' => ['normalizations.update', $normalization->slug], 'method' => 'patch', 'data-test'=>$htmlTag.'-edit-form', 'class'=>'validate', 'name'=>$htmlTag.'-edit']) !!}

                            @include('lemurbot::normalizations.fields')

                            <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route('normalizations.index') }}" class="btn btn-default">Cancel</a>
                            </div>


                       {!! Form::close() !!}
                    </div>
               </div>
           </div>
       </div>
   </div>

@endsection
@push('scripts')
    {{ Html::script('js/validation.js') }}
    {{ Html::script('js/select2.js') }}
@endpush
