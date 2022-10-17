<!-- Slug Field -->
@if( !empty($language) && !empty($language->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\Language::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                <span name='unlock' id='openEditSlugDataTableModal' data-id="{{$language->slug}}" data-test='slug-unlock-button'
                      class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

@endif



<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="name_div">
    {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\Language::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>

<div class="clearfix"></div>


<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="description_div">
    {!! Form::label('description', 'Description:', ['data-test'=>"description_label"]) !!}
    {!! Form::text('description', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\Language::getFormValidation('description'),'id'=>"description_field", 'data-test'=>"description_field"] ) !!}
</div>

<div class="clearfix"></div>
