<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $botPlugin->slug }}</p>
</div>

<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'User Id:') !!}
    <p>{{ $botPlugin->user_id }}</p>
</div>

<!-- Bot Id Field -->
<div class="form-group">
    {!! Form::label('bot_id', 'Bot Id:') !!}
    <p>{{ $botPlugin->bot_id }}</p>
</div>

<!-- Plugin Id Field -->
<div class="form-group">
    {!! Form::label('plugin_id', 'Plugin Id:') !!}
    <p>{{ $botPlugin->plugin_id }}</p>
</div>

