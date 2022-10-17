<!-- Conversation Id Field -->
<div class="form-group">
    {!! Form::label('conversation_id', 'Conversation Id:') !!}
    <p>{{ $wildcard->conversation_id }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $wildcard->slug }}</p>
</div>

<!-- Type Field -->
<div class="form-group">
    {!! Form::label('type', 'Type:') !!}
    <p>{{ $wildcard->type }}</p>
</div>

<!-- Value Field -->
<div class="form-group">
    {!! Form::label('value', 'Value:') !!}
    <p>{{ $wildcard->value }}</p>
</div>

