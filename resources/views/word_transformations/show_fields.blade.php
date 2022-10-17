<!-- User Id Field -->
<div class="form-group">
    {!! Form::label('user_id', 'Created By:') !!}
    <p>{{ $wordTransformation->user->email }}</p>
</div>

<!-- Language Id Field -->
<div class="form-group">
    {!! Form::label('language_id', 'Language:') !!}
    <p>{{ $wordTransformation->language->name }}</p>
</div>

<!-- Slug Field -->
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}
    <p>{{ $wordTransformation->slug }}</p>
</div>

<!-- First Person Form Field -->
<div class="form-group">
    {!! Form::label('first_person_form', 'First Person Form:') !!}
    <p>{{ $wordTransformation->first_person_form }}</p>
</div>

<!-- Second Person Form Field -->
<div class="form-group">
    {!! Form::label('second_person_form', 'Second Person Form:') !!}
    <p>{{ $wordTransformation->second_person_form }}</p>
</div>

<!-- Third Person Form Field -->
<div class="form-group">
    {!! Form::label('third_person_form', 'Third Person Form:') !!}
    <p>{{ $wordTransformation->third_person_form }}</p>
</div>

<!-- Third Person Form Female Field -->
<div class="form-group">
    {!! Form::label('third_person_form_female', 'Third Person Form Female:') !!}
    <p>{{ $wordTransformation->third_person_form_female }}</p>
</div>

<!-- Third Person Form Male Field -->
<div class="form-group">
    {!! Form::label('third_person_form_male', 'Third Person Form Male:') !!}
    <p>{{ $wordTransformation->third_person_form_male }}</p>
</div>

<!-- Is Master Field -->
<div class="form-group">
    {!! Form::label('is_master', 'Is Master:') !!}
    <p>{{ $wordTransformation->is_master }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated:') !!}
    <p>{{ $wordTransformation->updated_at }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $wordTransformation->created_at }}</p>
</div>
