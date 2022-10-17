<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">


    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>



        @if(count($wordSpellingGroups)<=0)

            <div class="alert alert-info">There are no {!! strtolower($title) !!} associated with this bot </div>

        @else

            {!! Form::open(['route' => 'botWordSpellingGroups.store', 'data-test'=>$htmlTag.'-create-form', 'class'=>'validate', 'name'=>$htmlTag.'-create']) !!}


                    @foreach($wordSpellingGroups as $index => $wordSpellingGroup)


                                {!! Form::hidden('bulk', 1) !!}
                                {!! Form::hidden('redirect_url', url()->current(),['data-test'=>$wordSpellingGroup->word_spelling_group_slug."-redirect-url"] ) !!}
                                {!! Form::hidden('bot_id', $bot->slug,['data-test'=>$wordSpellingGroup->word_spelling_group_slug."-bot_id"] ) !!}

                                {!! Form::hidden('word_spelling_group_id['.$index.']', $wordSpellingGroup->word_spelling_group_slug,['data-test'=>$wordSpellingGroup->word_spelling_group_slug."-word_spelling_group_id"] ) !!}



                                <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='{!! $wordSpellingGroup->name !!}_div'>
                                    <label for='{!! $wordSpellingGroup->name !!}_field' data-test='{!! $wordSpellingGroup->name !!}_label'>Spellchecher - {!! $wordSpellingGroup->name !!}:</label>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>

                                            <input type='hidden' name='linked[{!! $index !!}]' value='0'>

                                            @if(empty($wordSpellingGroup->is_linked) )
                                                @php $checked = '' @endphp
                                            @else
                                                @php $checked = 'checked=\'true\'' @endphp

                                            @endif

                                        <input type='checkbox' name='linked[{!! $index !!}]' value='1' {!! $checked !!}  id='{!! $wordSpellingGroup->name !!}_link_field' {$validation} data-test='{!! $wordSpellingGroup->name !!}_link_field'>
                                        </span>

                                        <input type='text' value='{!! strtolower($wordSpellingGroup->name) !!}' class='form-control' id='{!! $wordSpellingGroup->name !!}_value_field' data-test='{!! $wordSpellingGroup->name !!}_value_field'>
                                        <div class='input-group-btn'>

                                            <a data-title="{!! ucwords($wordSpellingGroup->name) !!}" data-description="{!! $wordSpellingGroup->description !!}" id='{!! $wordSpellingGroup->word_spelling_group_slug !!}_info_button' data-test='{!! $wordSpellingGroup->word_spelling_group_slug !!}_info_button' class='btn btn-info open-info-button'><i class='fa fa-info-circle'></i></a>
                                            <a href="{!! url('wordSpellingGroups/'.$wordSpellingGroup->word_spelling_group_slug.'/edit') !!}" id='{!! $wordSpellingGroup->word_spelling_group_slug !!}_edit_button' data-test='{!! $wordSpellingGroup->word_spelling_group_slug !!}_edit_button' class='btn btn-success edit-button'><i class='fa fa-edit'></i></a>
                                            <a href="{!! url('wordSpellingDownload/'.$wordSpellingGroup->word_spelling_group_slug) !!}" type='link' id='{!! $wordSpellingGroup->word_spelling_group_slug !!}_download_button' data-test='{!! $wordSpellingGroup->word_spelling_group_slug !!}_download_button' class='btn btn-primary download-button'><i class='fa fa-download'></i></a>
                                        </div>
                                    </div>
                                </div>




            @endforeach

        <!-- Submit Field -->
            <div class="form-group col-sm-12">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <button type="reset" class="btn btn-default">Reset</button>
            </div>

            {!! Form::close() !!}


        @endif

        </div>
</section>
@push('scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click','.open-info-button',function(){


                let title = $(this).attr('data-title');
                let description = $(this).attr('data-description');

                //coin field - which should be disabled in this form
                $('div#showInfoModal p#info-body').html(description);
                $('div#showInfoModal #info-title').html(title);

                $('div#showInfoModal').modal('show');
            });


        });
    </script>
@endpush
<div class="modal" id="showInfoModal" tabindex="-1" role="dialog" data-test='show-info-modal'>
    <div class="modal-dialog modal-lg info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="info-title"></h4>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                <p id="info-body"></p>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <!-- Submit Field -->
                <div class="form-group col-sm-12">
                    <button type="button" class="btn btn-secondary" data-test="show-info-modal-close" data-dismiss="modal">Close</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
