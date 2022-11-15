<!-- User Id Field -->
@if( !empty($wordSpelling) && !empty($wordSpelling->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $wordSpelling->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

@endif

<div class="clearfix"></div>

<!-- Slug Field -->
@if( !empty($wordSpelling) && !empty($wordSpelling->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\WordSpelling::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

@endif

<div class="clearfix"></div>

@if(!empty($wordSpelling) && !empty($wordSpelling->wordSpellingGroup))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="word_spelling_group_id_div">
        {!! Form::label('word_spelling_group_id', 'Word Spelling Group:', ['data-test'=>"word_spelling_group_id_label"]) !!}
        <div class="input-group">
            {!! Form::text('word_spelling_group_id_text', $wordSpelling->wordSpellingGroup->name, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"word_spelling_group_id_text_field", 'data-test'=>"word_spelling_group_id_text_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('word_spelling_group_id')]) !!}
            {!! Form::hidden('word_spelling_group_id', $wordSpelling->wordSpellingGroup->slug, ['id'=>"word_spelling_group_id_field", 'data-test'=>"word_spelling_group_id_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('word_spelling_group_id')]) !!}

            <div class="input-group-btn">
                @if(!empty($wordSpelling)&&!empty($wordSpelling->word_spelling_group_id))
                    <a href="{!!url('/wordSpellingGroups/'.$wordSpelling->wordSpellingGroup->slug) !!}" id='wordSpellingGroup_button' class='btn btn-warning' data-test='wordSpellingGroup_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

@else

<!-- Word Spelling Group Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="word_spelling_group_id_div">
    {!! Form::label('word_spelling_group_id', 'Word Spelling Group:', ['data-test'=>"word_spelling_group_id_label"]) !!}
    {!! Form::select('word_spelling_group_id', $wordSpellingGroupList, (!empty($wordSpelling)?$wordSpelling->wordSpellingGroup->slug:(!empty($wordSpelling)?$wordSpelling->wordSpellingGroup->slug:"")), [  LemurEngine\LemurBot\Models\WordSpelling::getFormValidation('word_spelling_group_id'), 'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', 'data-test'=>"$htmlTag-word_spelling_group_id_select", 'id'=>"$htmlTag-word_spelling_group_id_select-select"]) !!}
</div>

<div class="clearfix"></div>

@endif


<!-- Slug Field -->
@if( !empty($wordSpelling) && !empty($wordSpelling->word) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="word_div">
        {!! Form::label('word', 'Word:', ['data-test'=>"word_label"]) !!}
        {!! Form::text('word', null, ['readonly'=>'readonly', 'class' => 'form-control', LemurEngine\LemurBot\Models\WordSpelling::getFormValidation('word'),'id'=>"word_field", 'data-test'=>"word_field"] ) !!}
        <small class="text-muted-wrapped">You cannot update words, only their replacements. Please delete and create a new word if you wish to change this.</small>
    </div>

@else

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="word_div">
        {!! Form::label('word', 'Word:', ['data-test'=>"word_label"]) !!}
        {!! Form::text('word', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\WordSpelling::getFormValidation('word'),'id'=>"word_field", 'data-test'=>"word_field"] ) !!}
    </div>

@endif



<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="replacement_div">
    {!! Form::label('replacement', 'Replacement:', ['data-test'=>"replacement_label"]) !!}
    {!! Form::text('replacement', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\WordSpelling::getFormValidation('replacement'),'id'=>"replacement_field", 'data-test'=>"replacement_field"] ) !!}
</div>


