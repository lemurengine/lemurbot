<!-- User Id Field -->
@if( !empty($customDoc) && !empty($customDoc->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $customDoc->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

    <div class="clearfix"></div>
@endif

<!-- Slug Field -->
@if( !empty($customDoc) && !empty($customDoc->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\BotKey::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>
    <div class="clearfix"></div>

@endif


<!-- Name Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="title_div">
    {!! Form::label('title', 'Title:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('title', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, LemurEngine\LemurBot\Models\BotKey::getFormValidation('name'),'id'=>"title_field", 'data-test'=>"title_field"] ) !!}
</div>

<div class="clearfix"></div>

<!-- Description Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="body_div">
    {!! Form::label('body', 'Body:', ['data-test'=>"body_label"]) !!}
    {!! Form::textarea('body', null, ['rows'=>3, 'class' => 'form-control','maxlength' => 100,'maxlength' => 100, LemurEngine\LemurBot\Models\BotKey::getFormValidation('body'),'id'=>"body_field", 'data-test'=>"body_field"] ) !!}
</div>

<div class="clearfix"></div>

