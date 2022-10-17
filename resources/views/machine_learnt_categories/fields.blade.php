<!-- Slug Field -->
@if( !empty($machineLearntCategory) && !empty($machineLearntCategory->slug) )

    <div class="form-group col-sm-6" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\MachineLearntCategory::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>

    <div class="clearfix"></div>


@endif



<!-- Client Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="client_id_div">
    {!! Form::label('client_id', 'ClientId:', ['data-test'=>"client_id_label"]) !!}
    <div class="input-group">
        {!! Form::text('client_id', $machineLearntCategory->client->slug, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"client_id_field", 'data-test'=>"client_id_field", LemurEngine\LemurBot\Models\Conversation::getFormValidation('client_id')]) !!}
        <div class="input-group-btn">
            @if(!empty($machineLearntCategory)&&!empty($machineLearntCategory->client->slug))
                <a href="{!!url('/clients/'.$machineLearntCategory->client->slug) !!}" id='client_button' class='btn btn-warning' data-test='client_button'><i class='fa fa-arrow-right'></i></a>
            @endif
        </div>
    </div>
</div>

<div class="clearfix"></div>

<!-- Bot Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="category_group_id_div">
    {!! Form::label('bot_slug', 'Bot:', ['data-test'=>"bot_slug_label"]) !!}
    <div class="input-group">
        {!! Form::text('', $machineLearntCategory->bot->name.' ('.$machineLearntCategory->bot->slug.')', ['disabled'=>'disabled', 'readonly'=>'readonly','class' => 'form-control', 'id'=>"bot_slug_field", 'data-test'=>"bot_slug_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('bot_id')]) !!}
        <div class="input-group-btn">
            @if(!empty($machineLearntCategory)&&!empty($machineLearntCategory->bot))
                <a href="{!!url('/bots/'.$machineLearntCategory->bot->slug) !!}" id='bot_button' class='btn btn-warning' data-test='bot_button'><i class='fa fa-arrow-right'></i></a>
            @endif
        </div>
    </div>
</div>

{!! Form::hidden('bot_id', $machineLearntCategory->bot->slug, ['data-test'=>"bot_id_hidden"]) !!}

<div class="clearfix"></div>

<!-- Turn Id Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="turn_id_div">
    {!! Form::label('turn_id', 'TurnId:', ['data-test'=>"turn_id_label"]) !!}
    <div class="input-group">
        {!! Form::text('turn_id', $machineLearntCategory->turn->slug, ['readonly'=>'readonly','class' => 'form-control', 'id'=>"turn_id_field", 'data-test'=>"turn_id_field", LemurEngine\LemurBot\Models\SetValue::getFormValidation('turn_id')]) !!}
        <div class="input-group-btn">
            @if(!empty($machineLearntCategory)&&!empty($machineLearntCategory->turn))
                <a href="{!!url('/turns/'.$machineLearntCategory->turn->slug) !!}" id='turn_button' class='btn btn-warning' data-test='turn_button'><i class='fa fa-arrow-right'></i></a>
            @endif
        </div>
    </div>
</div>

<div class="clearfix"></div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="pattern_div">
    {!! Form::label('pattern', 'Pattern:', ['data-test'=>"pattern_label"]) !!}
    {!! Form::text('pattern', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\MachineLearntCategory::getFormValidation('pattern'),'id'=>"pattern_field", 'data-test'=>"pattern_field"] ) !!}
</div>

<div class="clearfix"></div>

<!-- Template Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="template_div">
    {!! Form::label('template', 'Template:', ['data-test'=>"template_label"]) !!}
    {!! Form::textarea('template', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"template_field", 'data-test'=>"template_field", LemurEngine\LemurBot\Models\MachineLearntCategory::getFormValidation('template')] ) !!}
</div>

<div class="clearfix"></div>

<!-- Topic Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="topic_div">
    {!! Form::label('topic', 'Topic:', ['data-test'=>"topic_label"]) !!}
    {!! Form::text('topic', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\MachineLearntCategory::getFormValidation('topic'),'id'=>"topic_field", 'data-test'=>"topic_field"] ) !!}
</div>

<div class="clearfix"></div>

<!-- Topic Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="that_div">
    {!! Form::label('that', 'That:', ['data-test'=>"that_label"]) !!}
    {!! Form::text('that', null, ['class' => 'form-control', LemurEngine\LemurBot\Models\MachineLearntCategory::getFormValidation('that'),'id'=>"that_field", 'data-test'=>"that_field"] ) !!}
</div>

<div class="clearfix"></div>

<!-- Example Input Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="example_input_div">
    {!! Form::label('example_input', 'Example Input:', ['data-test'=>"example_input"]) !!}
    {!! Form::textarea('example_input', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"example_input_field", 'data-test'=>"example_input_field", LemurEngine\LemurBot\Models\MachineLearntCategory::getFormValidation('example_input')] ) !!}
</div>

<div class="clearfix"></div>

<!-- Example Output Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="example_output_div">
    {!! Form::label('example_output', 'Example Output:', ['data-test'=>"example_output"]) !!}
    {!! Form::textarea('example_output', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"example_output_field", 'data-test'=>"example_output_field", LemurEngine\LemurBot\Models\MachineLearntCategory::getFormValidation('example_output')] ) !!}
</div>

<!-- Category Group Slug Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="example_output_div">
    {!! Form::label('category_group_slug', 'Category Group Slug:', ['data-test'=>"category_group_slug"]) !!}
    {!! Form::textarea('category_group_slug', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"category_group_slug_field", 'data-test'=>"category_group_slug_field", LemurEngine\LemurBot\Models\MachineLearntCategory::getFormValidation('category_group_slug')] ) !!}
</div>

<div class="clearfix"></div>

<!-- Occurrences Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="occurrences_div">
    {!! Form::label('occurrences', 'Occurrences:', ['data-test'=>"occurrences"]) !!}
    {!! Form::textarea('occurrences', null, ['rows' => 2, 'class' => 'form-control', 'id'=>"occurrences_field", 'data-test'=>"occurrences_field", LemurEngine\LemurBot\Models\MachineLearntCategory::getFormValidation('occurrences')] ) !!}
</div>

<div class="clearfix"></div>
