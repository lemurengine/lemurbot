<!-- Client Id Field -->
<div class="form-group">
    {!! Form::label('client_id', 'Client Id:') !!}
    <p>{{ $clientCategory->client_id }}</p>
</div>

<!-- Bot Id Field -->
<div class="form-group">
    {!! Form::label('bot_id', 'Bot:') !!}
    <p>{{ $clientCategory->bot->name }}</p>
</div>

<!-- Conversation Log Id Field -->
<div class="form-group">
    {!! Form::label('turn_id', 'Conversation Log Id:') !!}
    <p>{{ $clientCategory->turn_id }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $clientCategory->slug }}</p>
</div>

<!-- Pattern Field -->
<div class="form-group">
    {!! Form::label('pattern', 'Pattern:') !!}
    <p>{{ $clientCategory->pattern }}</p>
</div>

<!-- Template Field -->
<div class="form-group">
    {!! Form::label('template', 'Template:') !!}
    <p>{{ $clientCategory->template }}</p>
</div>

<!-- Tag Field -->
<div class="form-group">
    {!! Form::label('tag', 'Tag:') !!}
    <p>{{ $clientCategory->tag }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $clientCategory->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $clientCategory->created_at }}</p>
</div>
