<!-- User Id Field -->
@if( !empty($botWordSpellingGroup) && !empty($botWordSpellingGroup->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $botWordSpellingGroup->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

    <div class="clearfix"></div>

@endif


@if(!empty($botWordSpellingGroup) && !empty($botWordSpellingGroup->bot))
    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="word_spelling_group_id_div">
        {!! Form::label('bot_id', 'Bot:', ['data-test'=>"bot_id_label"]) !!}
        <div class="input-group">
            {!! Form::text('bot_id', $botWordSpellingGroup->bot->name, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"bot_id_field", 'data-test'=>"bot_id_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('bot_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($botWordSpellingGroup)&&!empty($botWordSpellingGroup->bot_id))
                    <a href="{!!url('/bots/'.$botWordSpellingGroup->bot->slug) !!}" id='bot_button' class='btn btn-warning' data-test='bot_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

@else

    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="bot_id_div">
        {!! Form::label('bot_id', 'Bot Id:', ['data-test'=>"bot_id_label"]) !!}
        {!! Form::select('bot_id', $botList, (!empty($botWordSpellingGroup)?$botWordSpellingGroup->bot->slug:(!empty($botWordSpellingGroup)?$botWordSpellingGroup->bot->slug:"")), [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', LemurEngine\LemurBot\Models\BotWordSpellingGroup::getFormValidation('bot_id'), 'data-test'=>"$htmlTag-bot_id-select", 'id'=>"$htmlTag-bot_id-select"]) !!}
    </div>

    <div class="clearfix"></div>

@endif



<div class="clearfix"></div>

@if(!empty($botWordSpellingGroup) && !empty($botWordSpellingGroup->wordSpellingGroup))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="word_spelling_group_id_div">
        {!! Form::label('word_spelling_group_id', 'Word Spelling Group:', ['data-test'=>"word_spelling_group_id_label"]) !!}
        <div class="input-group">
            {!! Form::text('word_spelling_group_id', $botWordSpellingGroup->wordSpellingGroup->name, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"word_spelling_group_id_field", 'data-test'=>"word_spelling_group_id_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('word_spelling_group_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($botWordSpellingGroup)&&!empty($botWordSpellingGroup->word_spelling_group_id))
                    <a href="{!!url('/wordSpellingGroups/'.$botWordSpellingGroup->wordSpellingGroup->slug) !!}" id='wordSpellingGroup_button' class='btn btn-warning' data-test='wordSpellingGroup_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

@else

    <!-- Word Spelling Group Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="word_spelling_group_id_div">
        {!! Form::label('word_spelling_group_id', 'Word Spelling Group:', ['data-test'=>"word_spelling_group_id_label"]) !!}
        {!! Form::select('word_spelling_group_id', $wordSpellingGroupList, (!empty($botWordSpellingGroup)?$botWordSpellingGroup->wordSpellingGroup->slug:(!empty($botWordSpellingGroup)?$botWordSpellingGroup->wordSpellingGroup->slug:"")), [  LemurEngine\LemurBot\Models\WordSpelling::getFormValidation('word_spelling_group_id'), 'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', 'data-test'=>"$htmlTag-word_spelling_group_id_select", 'id'=>"$htmlTag-word_spelling_group_id_select-select"]) !!}
    </div>

    <div class="clearfix"></div>

@endif
