@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Your API Account Key
        </h1>
   </section>

   <div class="content">
       @include('lemurbot::layouts.feedback')
       <div class="box box-primary">
           <div class="box-body edit-page">
               <div class="row">
                   <div class="col-md-12">
                       <div class="col-md-12">
                           @if($token)

                           <div class="alert alert-success">
                               <p>Your new account key has been generated.</p>
                               <p>Please copy and save it - this is only time you will see it.</p>
                           </div>
                           @else
                               <div class="alert alert-info">
                                   <p>API account keys are encrypted and saved in the database and as such we cannot display them for you.</p>
                                   <p>If you want to get your API account key then press 'Generate' button.</p>
                                   <p>This is the only time your account key will be displayed for you so please make sure you save it.</p>
                                   <p>Regenerating a new account key will invalidate an existing key of the same type.</p>
                               </div>
                               <div class="alert alert-warning">
                                   <p>This key is for managing your bots.</p>
                                   <p>If you want to allow access to a private bot you need to generate a 'Bot Key'</p>
                               </div>
                           @endif
                       </div>
                   </div>
                   <div class="col-md-12">

                       @if($token)
                           <div class="form-group col-sm-12 col-md-6" data-test="email_div">
                               {!! Form::label('new_key', 'New Key:', ['data-test'=>"new_key"]) !!}
                               <div class="input-group">
                                   {!! Form::text('new_key', $token, ['readonly'=>'readonly', 'class' => 'form-control','id'=>"new_key_field", 'data-test'=>"new_key_field"] ) !!}
                                   <div class="input-group-btn">
                                       <button type="button" value="copy_key_button" name="copy_key_button" class='btn btn-success copy-key-button' id='copy_key_button' data-test='copy_key_button'><i class='fa fa-copy'></i> Copy</button>
                                   </div>
                               </div>

                           </div>

                           <!-- Submit Field -->
                           <div class="form-group col-sm-12">
                               <a href="{{ url('/tokens') }}" class="btn btn-default">Back</a>
                               <a href="{{ url('/profile') }}" class="btn btn-default">Profile</a>
                           </div>
                       @else


                       <div class="clearfix"></div>
                           {!! Form::open(['url' => '/tokens', 'id'=>'api_account_key_form', 'data-test'=>'api_key_form', 'class'=>'validate', 'name'=>'api_key_form']) !!}
                            {!! Form::hidden('key_type','account_key') !!}
                       <!-- Key Field -->
                       <div class="form-group col-sm-12 col-md-6" data-test="account_key_div">
                           {!! Form::label('account_key', 'Account Key:', ['data-test'=>"account_key_label"]) !!}
                           <div class="input-group">
                               {!! Form::text('', 'Full access key to talk, bot and account admin', ['readonly'=>'readonly', 'class' => 'form-control','id'=>"account_key_field", 'data-test'=>"account_key_field"] ) !!}
                               <div class="input-group-btn">
                                   <button type="button" class='btn btn-success generate-api-key-button' id='generate_account_key_button' data-test='generate_account_key_button'><i class='fa fa-refresh'></i> Generate</button>
                               </div>
                           </div>

                       </div>
                           {!! Form::close() !!}

                           <!-- Submit Field -->
                           <div class="form-group col-sm-12">
                               <a href="{{ url('/profile') }}" class="btn btn-default">Profile</a>
                           </div>



                           @endif


                    </div>
               </div>
           </div>
       </div>
   </div>
@endsection
@push('scripts')
    {{ Html::script('vendor/lemurbot/js/validation.js') }}
    {{ Html::script('vendor/lemurbot/js/select2.js') }}
    <script>
        $('#copy_key_button').click(function(){

            /* Get the text field */
            var copyText = document.getElementById("new_key_field");

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            document.execCommand("copy");

            /* Alert the copied text */
            bootbox.alert("Key copied to clipboard: " + copyText.value);
        })


        $('#generate_account_key_button').click(function(e){
            e.preventDefault();
            bootbox.confirm({
                title: "Please confirm",
                message: "Pressing this button will invalidate the existing account key and generate a new one.<br/>Are you sure you want to continue?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success pull-right'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger pull-left'
                    }
                },
                callback: function (result) {
                    console.log('This was logged in the callback: ' + result);
                    if(result){
                        $('form#api_account_key_form').submit();
                    }else{
                        return;
                    }
                }
            });
        })

        $('#generate_bot_key_button').click(function(e){
            e.preventDefault();
            bootbox.confirm({
                title: "Please confirm",
                message: "Pressing this button will invalidate the existing bot key and generate a new one.<br/>Are you sure you want to continue?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success pull-right'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger pull-left'
                    }
                },
                callback: function (result) {
                    console.log('This was logged in the callback: ' + result);
                    if(result){
                        $('form#api_bot_key_form').submit();
                    }else{
                        return;
                    }
                }
            });
        })
    </script>
@endpush
