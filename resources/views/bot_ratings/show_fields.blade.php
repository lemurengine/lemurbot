<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $botRating->slug }}</p>
</div>

<!-- Conversation Id Field -->
<div class="form-group">
    {!! Form::label('conversation_id', 'Conversation Id:') !!}
    <p>{{ $botRating->conversation_id }}</p>
</div>

<!-- Bot Id Field -->
<div class="form-group">
    {!! Form::label('bot_id', 'Bot Id:') !!}
    <p>{{ $botRating->bot_id }}</p>
</div>

<!-- Rating Field -->
<div class="form-group">
    {!! Form::label('rating', 'Rating:') !!}
    <p>{{ $botRating->rating }}</p>
</div>

