@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Your Profile
        </h1>
   </section>

   <div class="content">
       @include('lemurbot::layouts.feedback')
       <div class="box box-primary">
           <div class="box-body edit-page">
               <div class="row">
                   <div class="col-md-12">

                       {!! Form::open(['url' => '/profile', 'data-test'=>$htmlTag.'-edit-form', 'class'=>'validate', 'name'=>$htmlTag.'-edit-form']) !!}

                       {!! Form::hidden('user_id', $user->slug, [ 'data-validation'=>"required", 'id'=>"user_id_field", 'data-test'=>"user_id_field"] ) !!}


                       <div class="form-group col-sm-12 col-md-3" data-test="name_div">
                           {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
                           {!! Form::text('name', $user->name, ['class' => 'form-control', LemurEngine\LemurBot\Models\User::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
                       </div>

                       <div class="clearfix"></div>

                       <!-- Email Field -->
                       <div class="form-group col-sm-12 col-md-3" data-test="email_div">
                           {!! Form::label('email', 'Email:', ['data-test'=>"email_label"]) !!}
                           {!! Form::email('email', $user->email, ['class' => 'form-control', 'id'=>"email_field", 'data-test'=>"email_field", LemurEngine\LemurBot\Models\User::getFormValidation('email')]) !!}
                       </div>

                       <div class="clearfix"></div>

                       <!-- Email Field -->
                       <div class="form-group col-sm-12 col-md-3" data-test="email_div">
                           {!! Form::label('password', 'Password:', ['data-test'=>"password_label"]) !!}
                           <input type="password" autocomplete="false" name="password" class="form-control" id="password_field"  data-test="password_field" data-validation="required" />
                           <span class="help-block form-info">Please add your password to make changes to your profile</span>
                        </div>

                       <div class="clearfix"></div>

                            <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route($link.'.index') }}" class="btn btn-default">Cancel</a>

                            </div>

                       <div class="form-group col-sm-12 col-md-3" data-test="email_div">
                            <span class="help-block form-info">If you want to change your password, just log out and use the forgotten password feature</span>
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
