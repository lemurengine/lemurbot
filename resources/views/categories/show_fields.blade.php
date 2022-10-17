<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'Created By:') !!}
    <p>{{ $category->user->email }}</p>
</div>

<!-- Category Group Id Field -->
<div class="form-group">
    {!! Form::label('category_group_id', 'Category Group Id:') !!}
    <p>{{ $category->category_group_id }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $category->slug }}</p>
</div>

<!-- Pattern Field -->
<div class="form-group">
    {!! Form::label('pattern', 'Pattern:') !!}
    <p>{{ $category->pattern }}</p>
</div>

<!-- Regexp Pattern Field -->
<div class="form-group">
    {!! Form::label('regexp_pattern', 'Regexp Pattern:') !!}
    <p>{{ $category->regexp_pattern }}</p>
</div>

<!-- First Letter Pattern Field -->
<div class="form-group">
    {!! Form::label('first_letter_pattern', 'First Letter Pattern:') !!}
    <p>{{ $category->first_letter_pattern }}</p>
</div>

<!-- Topic Field -->
<div class="form-group">
    {!! Form::label('topic', 'Topic:') !!}
    <p>{{ $category->topic }}</p>
</div>

<!-- Regexp Topic Field -->
<div class="form-group">
    {!! Form::label('regexp_topic', 'Regexp Topic:') !!}
    <p>{{ $category->regexp_topic }}</p>
</div>

<!-- First Letter Topic Field -->
<div class="form-group">
    {!! Form::label('first_letter_topic', 'First Letter Topic:') !!}
    <p>{{ $category->first_letter_topic }}</p>
</div>

<!-- That Field -->
<div class="form-group">
    {!! Form::label('that', 'That:') !!}
    <p>{{ $category->that }}</p>
</div>

<!-- Regexp That Field -->
<div class="form-group">
    {!! Form::label('regexp_that', 'Regexp That:') !!}
    <p>{{ $category->regexp_that }}</p>
</div>

<!-- First Letter That Field -->
<div class="form-group">
    {!! Form::label('first_letter_that', 'First Letter That:') !!}
    <p>{{ $category->first_letter_that }}</p>
</div>

<!-- Template Field -->
<div class="form-group">
    {!! Form::label('template', 'Template:') !!}
    <p>{{ $category->template }}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $category->status }}</p>
</div>

