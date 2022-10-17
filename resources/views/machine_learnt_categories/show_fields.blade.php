<!-- Client Id Field -->
<div class="form-group">
    {!! Form::label('client_id', 'Client Id:') !!}
    <p>{{ $machineLearntCategory->client_id }}</p>
</div>

<!-- Bot Id Field -->
<div class="form-group">
    {!! Form::label('bot_id', 'Bot:') !!}
    <p>{{ $machineLearntCategory->bot->name }}</p>
</div>

<!-- Conversation Log Id Field -->
<div class="form-group">
    {!! Form::label('turn_id', 'Conversation Log Id:') !!}
    <p>{{ $machineLearntCategory->turn_id }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $machineLearntCategory->slug }}</p>
</div>

<!-- Pattern Field -->
<div class="form-group">
    {!! Form::label('pattern', 'Pattern:') !!}
    <p>{{ $machineLearntCategory->pattern }}</p>
</div>

<!-- Template Field -->
<div class="form-group">
    {!! Form::label('template', 'Template:') !!}
    <p>{{ $machineLearntCategory->template }}</p>
</div>

<!-- Topic Field -->
<div class="form-group">
    {!! Form::label('topic', 'Topic:') !!}
    <p>{{ $machineLearntCategory->topic }}</p>
</div>

<!-- That Field -->
<div class="form-group">
    {!! Form::label('that', 'That:') !!}
    <p>{{ $machineLearntCategory->that }}</p>
</div>

<!-- Example Input Field -->
<div class="form-group">
    {!! Form::label('example_input', 'Example Input:') !!}
    <p>{{ $machineLearntCategory->example_input }}</p>
</div>

<!-- Example Output Field -->
<div class="form-group">
    {!! Form::label('example_output', 'Example Output:') !!}
    <p>{{ $machineLearntCategory->example_output }}</p>
</div>

<!-- Category Group Slug Field -->
<div class="form-group">
    {!! Form::label('category_group_slug', 'Category Group Slug:') !!}
    <p>{{ $machineLearntCategory->category_group_slug }}</p>
</div>

<!-- Occurrences Field -->
<div class="form-group">
    {!! Form::label('occurrences', 'Occurrences:') !!}
    <p>{{ $machineLearntCategory->occurrences }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $machineLearntCategory->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $machineLearntCategory->created_at }}</p>
</div>
