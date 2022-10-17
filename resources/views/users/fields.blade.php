<!-- Slug Field -->
@if( !empty($user) && !empty($user->slug) )

    <div class="form-group col-sm-12 col-md-6 col-lg-6" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\User::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                <span name='unlock' id='openEditSlugDataTableModal' data-id="{{$user->slug}}" data-test='slug-unlock-button'
                      class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>
    </div>

@endif

<div class="clearfix"></div>

<div class="form-group col-sm-12 col-md-6 col-lg-6" data-test="name_div">
    {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\User::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>

<div class="clearfix"></div>

<!-- Email Field -->
<div class="form-group col-sm-12 col-md-6 col-lg-6" data-test="email_div">
    {!! Form::label('email', 'Email:', ['data-test'=>"email_label"]) !!}
    {!! Form::email('email', null, ['class' => 'form-control', 'id'=>"email_field", 'data-test'=>"email_field", LemurEngine\LemurBot\Models\User::getFormValidation('email')]) !!}
</div>

<div class="clearfix"></div>

<!-- User Role Field -->
<div class="form-group col-sm-12 col-md-6 col-lg-6 select2" data-test="user_role_div">
    {!! Form::label('user_role', 'User Role:', ['data-test'=>"user_role_label"]) !!}
    {!! Form::select('user_role', ['author'=>'Author', 'admin'=>'Admin'], (!empty($user) && LemurPriv::isAdmin($user)?"admin":"author"), [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', LemurEngine\LemurBot\Models\Bot::getFormValidation('language_id'), 'data-test'=>"$htmlTag-language_id-select", 'id'=>"$htmlTag-language_id-select"]) !!}
</div>

<div class="clearfix"></div>
