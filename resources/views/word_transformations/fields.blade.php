<!-- User Id Field -->
@if( !empty($wordTransformation) && !empty($wordTransformation->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $wordTransformation->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif

<!-- Slug Field -->
@if( !empty($wordTransformation) && !empty($wordTransformation->slug))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\WordTransformation::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

    <div class="clearfix"></div>
@endif

<!-- Language Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="language_id_div">
    {!! Form::label('language_id', 'Language:', ['data-test'=>"language_id_label"]) !!}
    {!! Form::select('language_id', $languageList, (!empty($wordTransformation)?$wordTransformation->language->slug:(!empty($wordTransformation)?$wordTransformation->language->slug:"")), [ LemurEngine\LemurBot\Models\WordTransformation::getFormValidation('language_id'), 'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', 'data-test'=>"$htmlTag-language_id-select", 'id'=>"$htmlTag-language_id-select"]) !!}
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="first_person_form_div">
    {!! Form::label('first_person_form', 'First Person Form:', ['data-test'=>"first_person_form_label"]) !!}
    {!! Form::text('first_person_form', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\WordTransformation::getFormValidation('first_person_form'),'id'=>"first_person_form_field", 'data-test'=>"first_person_form_field"] ) !!}
</div>


<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="second_person_form_div">
    {!! Form::label('second_person_form', 'Second Person Form:', ['data-test'=>"second_person_form_label"]) !!}
    {!! Form::text('second_person_form', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\WordTransformation::getFormValidation('second_person_form'),'id'=>"second_person_form_field", 'data-test'=>"second_person_form_field"] ) !!}
</div>


<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="third_person_form_div">
    {!! Form::label('third_person_form', 'Third Person Form:', ['data-test'=>"third_person_form_label"]) !!}
    {!! Form::text('third_person_form', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\WordTransformation::getFormValidation('third_person_form'),'id'=>"third_person_form_field", 'data-test'=>"third_person_form_field"] ) !!}
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="third_person_form_female_div">
    {!! Form::label('third_person_form_female', 'Third Person Form Female:', ['data-test'=>"third_person_form_female_label"]) !!}
    {!! Form::text('third_person_form_female', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\WordTransformation::getFormValidation('third_person_form_female'),'id'=>"third_person_form_female_field", 'data-test'=>"third_person_form_female_field"] ) !!}
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="third_person_form_male_div">
    {!! Form::label('third_person_form_male', 'Third Person Form Male:', ['data-test'=>"third_person_form_male_label"]) !!}
    {!! Form::text('third_person_form_male', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\WordTransformation::getFormValidation('third_person_form_male'),'id'=>"third_person_form_male_field", 'data-test'=>"third_person_form_male_field"] ) !!}
</div>

<div class="clearfix"></div>

@if(LemurPriv::isAdmin(Auth::user()))
<!-- 'Boolean Is Master Field' checked by default -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="is_master_div">
    {!! Form::label('is_master', 'Is Master:', ['data-test'=>"is_master_label"]) !!}
    <div class="input-group" data-test="is_master_group">
        <span class="input-group-addon">
            {!! Form::hidden('is_master', 0) !!}
            @if(empty($wordTransformation) || $wordTransformation->is_master==0 || !$wordTransformation->is_master)
                @php $checked = ''; @endphp
            @else
                @php $checked = true; @endphp
            @endif
            {{ Form::checkbox('is_master', '1', $checked, ['id'=>"is_master_field", 'data-test'=>"is_master_field"])  }}
         </span>
        <input type="text" class="form-control" aria-label="..." value="Is Master?">
    </div><!-- /.col-lg-6 -->
</div>
<div class="clearfix"></div>
@endif




