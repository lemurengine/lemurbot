<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $conversation->slug }}</p>
</div>

<!-- Bot Id Field -->
<div class="form-group">
    {!! Form::label('bot_id', 'Bot:') !!}
    <p>{{ $conversation->bot->name }}</p>
</div>

<!-- Client Id Field -->
<div class="form-group">
    {!! Form::label('client_id', 'Client Id:') !!}
    <p>{{ $conversation->client_id }}</p>
</div>

<!-- Allow HTML Field -->
<div class="form-group">
    {!! Form::label('allow_html', 'Allow HTML:') !!}
    <p>{{ $conversation->allow_html }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $conversation->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $conversation->created_at }}</p>
</div>
