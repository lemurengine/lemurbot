<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'Created By:') !!}
    <p>{{ $wordSpelling->user->email }}</p>
</div>

<!-- Word Spelling Group Id Field -->
<div class="form-group">
    {!! Form::label('word_spelling_group_id', 'Word Spelling Group Id:') !!}
    <p>{{ $wordSpelling->word_spelling_group_id }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $wordSpelling->slug }}</p>
</div>

<!-- Word Field -->
<div class="form-group">
    {!! Form::label('word', 'Word:') !!}
    <p>{{ $wordSpelling->word }}</p>
</div>

<!-- Replacement Field -->
<div class="form-group">
    {!! Form::label('replacement', 'Replacement:') !!}
    <p>{{ $wordSpelling->replacement }}</p>
</div>

