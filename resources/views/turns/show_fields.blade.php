<!-- Conversation Id Field -->
<div class="form-group">
    {!! Form::label('conversation_id', 'Conversation Id:') !!}
    <p>{{ $turn->conversation_id }}</p>
</div>

<!-- Category Id Field -->
<div class="form-group">
    {!! Form::label('category_id', 'Category Id:') !!}
    <p>{{ $turn->category_id }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $turn->slug }}</p>
</div>

<!-- Input Field -->
<div class="form-group">
    {!! Form::label('input', 'Input:') !!}
    <p>{{ $turn->input }}</p>
</div>

<!-- Output Field -->
<div class="form-group">
    {!! Form::label('output', 'Output:') !!}
    <p>{{ $turn->output }}</p>
</div>

<!-- Source Field -->
<div class="form-group">
    {!! Form::label('source', 'Source:') !!}
    <p>{{ $turn->source }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $turn->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $turn->created_at }}</p>
</div>
