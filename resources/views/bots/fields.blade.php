<!-- User Id Field -->
@if( !empty($bot) && !empty($bot->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $bot->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>



@endif


<!-- Slug Field -->
@if( !empty($bot) && !empty($bot->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        <div class="input-group">
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\Bot::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
            <div class="input-group-btn">
                <span name='unlock' id='openEditSlugDataTableModal' data-id="{{$bot->slug}}" data-test='slug-unlock-button'
                      class='btn btn-danger slug-unlock-button'><i class='fa fa-lock'></i></span>
            </div>
        </div>
    </div>

@endif


<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="name_div">
    {!! Form::label('image', 'Image:', ['data-test'=>"image_label"]) !!}
    <div class="avatar-wrapper">
        @if(!empty($bot)&&!empty($bot->image))
            @php $img = $bot->imageUrl; @endphp
            @php $imgFilename = $bot->image; @endphp
        @else
            @php $img = LemurEngine\LemurBot\Models\Bot::getDefaultImageUrl(false); @endphp
            @php $imgFilename = ''; @endphp
        @endif

        <img class="bot image-pic" src="{!! $img !!}" />
        <div class="bot upload-button">
            <i class="fa fa-arrow-circle-up" aria-hidden="true"></i>
        </div>
        <input name="image-filename" data-test="bot-image-filename"  type="hidden" value="{!! $imgFilename !!}"/>
        <input class="bot image-upload" name="image" id="bot-image" data-test="bot-image"  type="file" accept="image/*"/>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.bot.image-pic').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(".bot.image-upload").on('change', function(){
                readURL(this);
            });

            $(".bot.upload-button").on('click', function() {
                $(".bot.image-upload").click();
            });
        });
    </script>
@endpush
<div class="form-group col-lg-4 col-md-4 col-sm-12" data-test="name_div">
    {!! Form::label('name', 'Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['placeholder'=>'Movie Bot', 'readonly'=>$readonly, 'class' => 'form-control', LemurEngine\LemurBot\Models\Bot::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>
<!-- Language Id Field -->
<div class="form-group col-lg-2 col-md-2 col-sm-12 select2" data-test="language_id_div">
    {!! Form::label('language_id', 'Language:', ['data-test'=>"language_id_label"]) !!}
    {!! Form::select('language_id', $languageList, (!empty($bot)?$bot->language->slug:(!empty($bot)?$bot->language->slug:"")), ['disabled'=>$readonly, 'readonly'=>$readonly, 'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', LemurEngine\LemurBot\Models\Bot::getFormValidation('language_id'), 'data-test'=>"$htmlTag-language_id-select", 'id'=>"$htmlTag-language_id-select"]) !!}
</div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="summary_div">
    {!! Form::label('summary', 'Summary:', ['data-test'=>"summary_label"]) !!}
    {!! Form::text('summary', null, ['placeholder'=>'A Movie Buff Bot', 'readonly'=>$readonly, 'class' => 'form-control', LemurEngine\LemurBot\Models\Bot::getFormValidation('summary'),'id'=>"summary_field", 'data-test'=>"summary_field"] ) !!}
</div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="description_div">
    {!! Form::label('description', 'Description:', ['data-test'=>"description_label"]) !!}
    {!! Form::textarea('description', null, ['placeholder'=>'This bot talks about movies', 'readonly'=>$readonly, 'rows'=>4, 'class' => 'form-control', LemurEngine\LemurBot\Models\Bot::getFormValidation('description'),'id'=>"description_field", 'data-test'=>"description_field"] ) !!}
</div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="default_response">
    {!! Form::label('default_response', 'Default Response:', ['data-test'=>"default_response_label"]) !!}
    {!! Form::text('default_response', config('lemurbot.properties.default_response'), ['placeholder'=>'Sorry, I dont\'t understand.', 'readonly'=>$readonly,  'class' => 'form-control', LemurEngine\LemurBot\Models\Bot::getFormValidation('default_response'),'id'=>"default_response_field", 'data-test'=>"default_response_field"] ) !!}
    <small class="help-block text-muted-wrapped" data-test="">This is the response which is returned if no matching AIML category is found</small>
</div>

<div class="form-group col-lg-12 col-md-12 col-sm-12" data-test="lemurtar_div">
    {!! Form::label('lemurtar_url', 'Lemurtar URL:', ['data-test'=>"lemurtar_label"]) !!}
    {!! Form::textarea('lemurtar_url', null, ['readonly'=>$readonly, 'rows'=>4, 'class' => 'form-control', LemurEngine\LemurBot\Models\Bot::getFormValidation('lemurtar_url'),'id'=>"lemurtar_urlfield", 'data-test'=>"lemurtar_url_field"] ) !!}
    <small class="help-block text-muted-wrapped" data-test="">Visit <a href="{!! config('lemurbot.portal.lemurtar_url') !!}">{!! config('lemurbot.portal.lemurtar_url') !!}</a> to generate a talking head avatar for your bot.</small>
</div>

<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="critical_category_group">
    {!! Form::label('critical_category_group', 'Critical Category Group:', ['data-test'=>"critical_category_group_label"]) !!}
    {!! Form::text('critical_category_group', config('lemurbot.properties.critical_category_filename'), ['placeholder'=>'std-critical', 'readonly'=>$readonly,  'class' => 'form-control', 'id'=>"critical_category_group_field", 'data-test'=>"critical_category_group_field"] ) !!}
    <small class="help-block text-muted-wrapped" data-test="">The setting this category group ensures that this group cannot be unlinked from the bot.</small>
</div>

<!-- Status Field -->
<div class="form-group col-lg-3 col-md-3 col-sm-6 select2" data-test="status_div">
    {!! Form::label('status', 'Status:', ['data-test'=>"status_label"]) !!}
    {!! Form::select('status', config('lemurbot.dropdown.item_status'), null, [ 'disabled'=>$readonly, 'readonly'=>$readonly,  'class' => 'form-control select2 first-option', LemurEngine\LemurBot\Models\Bot::getFormValidation('status'), 'data-test'=>"$htmlTag-status-select", 'id'=>"$htmlTag-status-select"]) !!}
</div>



<!-- 'Boolean Is Public Field' checked by default -->
<div class="form-group col-lg-3 col-md-3 col-sm-6" data-test="is_public_div">
    {!! Form::label('is_public', 'Is Public:', ['data-test'=>"is_public_label"]) !!}
    <div class="input-group" data-test="is_public_group">
        <span class="input-group-addon">
            {!! Form::hidden('is_public', 0) !!}
            @if(empty($bot) || $bot->is_public==0 || !$bot->is_public)
                @php $checked = ''; @endphp
            @else
                @php $checked = true; @endphp
            @endif
            {{ Form::checkbox('is_public', '1', $checked, ['disabled'=>$readonly, 'id'=>"is_public_field", 'data-test'=>"is_public_field"])  }}
         </span>
        <input type="text" {!! ($readonly?'readonly':'') !!} class="form-control" aria-label="..." value="Is Public?">
    </div><!-- /.col-lg-6 -->
</div>

<div class="clearfix"></div>


@push('scripts')
    <script>
        $(document).on('change', ':file', function() {
            var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });

        $(document).ready( function() {
            $(':file').on('fileselect', function(event, numFiles, label) {
                console.log(numFiles);
                console.log(label);
                $('input#avatar_field').val(label)
            });
        });
    </script>
@endpush
