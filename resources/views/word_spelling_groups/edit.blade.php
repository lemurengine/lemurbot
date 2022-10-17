@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Word Spelling Group
        </h1>
   </section>

   <div class="content">
   @include('lemurbot::layouts.feedback')
       <div class="box box-primary">
           <div class="box-body edit-page">
               <div class="row">
                   <div class="col-md-12">
                       {!! Form::model($wordSpellingGroup, ['route' => ['wordSpellingGroups.update', $wordSpellingGroup->slug], 'method' => 'patch', 'data-test'=>$htmlTag.'-edit-form', 'class'=>'validate', 'name'=>$htmlTag.'-edit']) !!}

                            @include('lemurbot::word_spelling_groups.fields')

                            <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route('wordSpellingGroups.index') }}" class="btn btn-default">Cancel</a>
                            </div>


                       {!! Form::close() !!}
                    </div>
               </div>
           </div>
       </div>
   </div>
@endsection
@push('scripts')
    {{ Html::script('vendor/lemurbot/js/validation.js?v=1') }}
    {{ Html::script('vendor/lemurbot/js/select2.js') }}
@endpush
