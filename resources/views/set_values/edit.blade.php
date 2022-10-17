@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Set Value
        </h1>
   </section>

   <div class="content">
   @include('lemurbot::layouts.feedback')
       <div class="box box-primary">
           <div class="box-body edit-page">
               <div class="row">
                   <div class="col-md-12">
                       {!! Form::model($setValue, ['route' => ['setValues.update', $setValue->slug], 'method' => 'patch', 'data-test'=>$htmlTag.'-edit-form', 'class'=>'validate', 'name'=>$htmlTag.'-edit']) !!}

                            @include('lemurbot::set_values.fields')

                            <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route('setValues.index') }}" class="btn btn-default">Cancel</a>
                            </div>


                       {!! Form::close() !!}
                    </div>
               </div>
           </div>
       </div>
   </div>

@endsection
@push('scripts')
    {{ Html::script('vendor/lemurbot/js/validation.js') }}
    {{ Html::script('vendor/lemurbot/js/select2.js') }}
@endpush
