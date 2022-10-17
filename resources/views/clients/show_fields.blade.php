<!-- Bot Id Field -->
<div class="form-group">
    {!! Form::label('bot_id', 'Bot:') !!}
    <p>{{ $client->bot->name }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $client->slug }}</p>
</div>

<!-- Is Banned Field -->
<div class="form-group">
    {!! Form::label('is_banned', 'Is Banned:') !!}
    <p>{{ $client->is_banned }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $client->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $client->created_at }}</p>
</div>
