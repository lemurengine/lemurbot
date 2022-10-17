<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'Created By:') !!}
    <p>{{ $botWordSpellingGroup->user->email }}</p>
</div>

<!-- Bot Id Field -->
<div class="form-group">
    {!! Form::label('bot_id', 'Bot:') !!}
    <p>{{ $botWordSpellingGroup->bot->name }}</p>
</div>

<!-- Word Spelling Group Id Field -->
<div class="form-group">
    {!! Form::label('word_spelling_group_id', 'Word Spelling Group Id:') !!}
    <p>{{ $botWordSpellingGroup->word_spelling_group_id }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $botWordSpellingGroup->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $botWordSpellingGroup->created_at }}</p>
</div>
