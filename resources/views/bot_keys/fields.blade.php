<!-- User Id Field -->
@if( !empty($botKey) && !empty($botKey->user_id))

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="user_id_div">
        {!! Form::label('user_id', 'Created By:', ['data-test'=>"user_id_label"]) !!}
        {!! Form::text('', $botKey->user->email, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"user_id_field", 'data-test'=>"user_id_field"]) !!}
    </div>

    <div class="clearfix"></div>
@endif



<!-- Slug Field -->
@if( !empty($botKey) && !empty($botKey->slug) )

    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="slug_div">
        {!! Form::label('slug', 'Slug:', ['data-test'=>"slug_label"]) !!}
        {!! Form::text('slug', null, ['class' => 'form-control', 'id'=>"slug_field", 'readonly'=>'readonly', LemurEngine\LemurBot\Models\BotKey::getFormValidation('slug'), 'data-test'=>"slug_field"]) !!}
    </div>
    <div class="clearfix"></div>

@endif


<!-- If we are calling this from inside a bot page then we want to limit it to the bot in question -->
@if( !empty($bot) && !empty($bot->slug) )

    {!! Form::hidden('bot_id', $bot->slug, ['data-test'=>"bot_id_label"]) !!}

@elseif(!empty($botKey) && !empty($botKey->bot))
    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="bot_slug_div">
        {!! Form::label('bot_slug', 'Bot:', ['data-test'=>"bot_slug_label"]) !!}
        <div class="input-group">
            {!! Form::text('', $botKey->bot->name.' ('.$botKey->bot->slug.')', ['disabled'=>'disabled', 'readonly'=>'readonly','class' => 'form-control', 'id'=>"bot_slug_field", 'data-test'=>"bot_slug_field", LemurEngine\LemurBot\Models\BotKey::getFormValidation('bot_id')]) !!}
            <div class="input-group-btn">
                @if(!empty($botKey)&&!empty($botKey->bot_id))
                    <a href="{!!url('/bots/'.$botKey->bot->slug) !!}" id='bot_button' class='btn btn-warning' data-test='bot_button'><i class='fa fa-arrow-right'></i></a>
                @endif
            </div>
        </div>
    </div>
    {{\Request::get('bot_id')}}
    {!! Form::hidden('bot_id', $botKey->bot->slug, ['data-test'=>"bot_id_hidden"]) !!}
    <div class="clearfix"></div>

@else

    <!-- Bot Id Field -->
    <div class="form-group col-lg-6 col-md-6 col-sm-12 select2" data-test="bot_id_div">
        {!! Form::label('bot_id', 'Bot:', ['data-test'=>"bot_id_label"]) !!}
        {!! Form::select('bot_id', $botList, (!empty($botKey)?$botKey->bot->slug:(!empty($botKey)?$botKey->bot->slug:"")), [  'placeholder'=>'Please Select', 'class' => 'form-control select2 generic', LemurEngine\LemurBot\Models\BotKey::getFormValidation('bot_id'), 'data-test'=>"$htmlTag-bot_id-select", 'id'=>"$htmlTag-bot_id-select"]) !!}
    </div>

    <div class="clearfix"></div>

@endif


<!-- Name Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="name_div">
    {!! Form::label('name', 'Key Name:', ['data-test'=>"name_label"]) !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255, LemurEngine\LemurBot\Models\BotKey::getFormValidation('name'),'id'=>"name_field", 'data-test'=>"name_field"] ) !!}
</div>

<div class="clearfix"></div>


<!-- Description Field -->
<div class="form-group col-lg-6 col-md-6 col-sm-12" data-test="description_div">
    {!! Form::label('description', 'Description:', ['data-test'=>"description_label"]) !!}
    {!! Form::textarea('description', null, ['rows'=>3, 'class' => 'form-control','maxlength' => 100,'maxlength' => 100, LemurEngine\LemurBot\Models\BotKey::getFormValidation('description'),'id'=>"description_field", 'data-test'=>"description_field"] ) !!}
</div>

<div class="clearfix"></div>

<!-- Value Field -->
<div class="form-group col-sm-12 col-md-6" data-test="email_div">
    {!! Form::label('value', 'Key:', ['data-test'=>"value_label"]) !!}
    <div class="input-group">
        {!! Form::text('value', null, ['readonly'=>'readonly','class' => 'form-control','maxlength' => 255,'maxlength' => 255, LemurEngine\LemurBot\Models\BotKey::getFormValidation('value'),'id'=>"value_field", 'data-test'=>"value_field"] ) !!}
        <div class="input-group-btn">
            <button type="button" class='btn btn-success generate-bot-key-button' id='generate_bot_key_button' data-test='generate_bot_key_button'><i class='fa fa-refresh'></i> Generate</button>
        </div>
    </div>

</div>
@push('scripts')
    {{ Html::script('vendor/lemurbot/js/validation.js') }}
    {{ Html::script('vendor/lemurbot/js/select2.js') }}
    <script>
        $('#copy_key_button').click(function(){

            /* Get the text field */
            var copyText = document.getElementById("new_key_field");

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            /* Copy the text inside the text field */
            document.execCommand("copy");

            /* Alert the copied text */
            bootbox.alert("Key copied to clipboard: " + copyText.value);
        })


        $('#generate_bot_key_button').click(function(e){
            e.preventDefault();

            if($('input#value_field').val()!=''){
                bootbox.confirm({
                    title: "Please confirm",
                    message: "If you update this key the existing key will be invalidated when you save.<br/>Are you sure you want to continue?",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success pull-right'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger pull-left'
                        }
                    },
                    callback: function (result) {
                        console.log('This was logged in the callback: ' + result);
                        if(result){
                            $('input#value_field').val(uuidv4())
                        }else{
                            return;
                        }
                    }
                });
            }else{
                $('input#value_field').val(uuidv4())
            }




            function uuidv4() {
                return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
                    (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
                );
            }

        })


    </script>
@endpush

