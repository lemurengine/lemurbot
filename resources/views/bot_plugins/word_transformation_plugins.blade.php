
        @if(count($wordSpellingGroups)<=0)

            <div class="alert alert-info">There are no word transformation plugins associated with this bot </div>

        @else

            {!! Form::open(['route' => 'botWordSpellingGroups.store', 'data-test'=>$htmlTag.'-create-form', 'class'=>'validate', 'name'=>$htmlTag.'-create']) !!}


                    @foreach($wordSpellingGroups as $index => $wordSpellingGroup)

                                {!! Form::hidden('bulk', 1) !!}
                                {!! Form::hidden('redirect_url', url()->current(),['data-test'=>$wordSpellingGroup->word_spelling_group_slug."-redirect-url"] ) !!}
                                {!! Form::hidden('bot_id', $bot->slug,['data-test'=>$wordSpellingGroup->word_spelling_group_slug."-bot_id"] ) !!}

                                {!! Form::hidden('word_spelling_group_id['.$index.']', $wordSpellingGroup->word_spelling_group_slug,['data-test'=>$wordSpellingGroup->word_spelling_group_slug."-word_spelling_group_id"] ) !!}



                                <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='{!! $wordSpellingGroup->name !!}_div'>
                                    <label for='{!! $wordSpellingGroup->name !!}_field' data-test='{!! $wordSpellingGroup->name !!}_label'>{!! $wordSpellingGroup->name !!}</label>
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


                                            <a href="{!! url('wordSpellings?col=0&q='.$wordSpellingGroup->word_spelling_group_slug) !!}"class="btn btn-warning show-button" data-test="show-button-0">
                                                <i class="fa fa-tree"></i>
                                            </a>

                                            <a href="{!! url('wordSpellingGroups/'.$wordSpellingGroup->word_spelling_group_slug.'/edit') !!}" id='{!! $wordSpellingGroup->word_spelling_group_slug !!}_edit_button' data-test='{!! $wordSpellingGroup->word_spelling_group_slug !!}_edit_button' class='btn btn-success edit-button'><i class='fa fa-edit'></i></a>
                                            <a href="{!! url('wordSpellings/'.$wordSpellingGroup->word_spelling_group_slug)."/download" !!}" type='link' id='{!! $wordSpellingGroup->word_spelling_group_slug !!}_download_button' data-test='{!! $wordSpellingGroup->word_spelling_group_slug !!}_download_button' class='btn btn-primary download-button'><i class='fa fa-download'></i></a>
                                        </div>
                                    </div>
                                    <small>id: {!! $wordSpellingGroup->word_spelling_group_slug !!} </small>
                                </div>




            @endforeach

        <!-- Submit Field -->
            <div class="form-group col-sm-12">
                {!! Form::submit('Save Word Transformations', ['class' => 'btn btn-primary']) !!}
                <button type="reset" class="btn btn-default">Reset Word Transformations</button>
            </div>

            {!! Form::close() !!}


        @endif
