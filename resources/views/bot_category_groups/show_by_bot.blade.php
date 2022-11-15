<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>





        @if(count($categoryGroups)<=0)

            <div class="alert alert-info">There are no {!! strtolower($title) !!} associated with this bot </div>

        @else

            <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='check_all_div'>
                <div class='input-group'>
                    <span class='input-group-addon'>
                        <input type='checkbox' name="all" id="checkall" data-test='check_all_field'>
                    </span>
                <input type='text' value='Check All' class='form-control' readonly="readonly">
                </div>
            </div>

            <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='check_all_div'>
                <div class='input-group'>
                    <span class='input-group-addon'>
                        <input type='checkbox' name="all" id="checksuggested" data-test='check_suggested_field'>
                    </span>
                    <input type='text' value='Check Suggested Files' class='form-control' readonly="readonly">
                </div>
                <small class="help-block text-muted-wrapped" data-test="">Select the default suggested files to capture 90% of all conversations</small>
            </div>






            {!! Form::open(['route' => 'botCategoryGroups.store', 'data-test'=>$htmlTag.'-create-form', 'class'=>'validate', 'name'=>$htmlTag.'-create']) !!}


            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <button type="reset" class="btn btn-default">Reset</button>
            </div>

            <div class="clearfix"></div>



            @foreach($allSections as $sectionId => $sectionGroup)

                @if(!empty($categoryGroups[$sectionId]))

                @php $sectionName = $sectionGroup['name']; @endphp
                @php $sectionSlug = $sectionGroup['slug']; @endphp
                @if($sectionGroup['default_state'] == 'open')
                    @php $sectionShow = 'true'; @endphp
                    @php $collapseShow = 'collapse in'; @endphp
                    @php $buttonName = 'showsections'; @endphp

                @else
                    @php $sectionShow = 'false'; @endphp
                    @php $collapseShow = 'collapse'; @endphp
                    @php $buttonName = 'hidden-sections'; @endphp
                @endif


            <!--open the previous collaspe box-->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{!! $allSections[$sectionId]['name'] !!} Section</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool {!! $buttonName !!}" data-toggle="collapse" href="#{!! $sectionSlug !!}" role="button" aria-expanded="{!! $sectionShow !!}" aria-controls="#{!! $sectionSlug !!}" data-test='{!! $sectionSlug !!}_expand_button'><i class="fa fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body {!! $collapseShow !!}" id="{!! $sectionSlug !!}" aria-expanded="{!! $sectionShow !!}">



                    @foreach($categoryGroups[$sectionId] as $index => $categoryGroup)

                                {!! Form::hidden('bulk', 1) !!}
                                {!! Form::hidden('redirect_url', url()->current(),['data-test'=>$categoryGroup->category_group_id."-redirect-url"] ) !!}
                                {!! Form::hidden('bot_id', $bot->slug,['data-test'=>$categoryGroup->category_group_id."-bot_id"] ) !!}
                                {!! Form::hidden('category_group_id['.$index.']', $categoryGroup->category_group_id,['data-test'=>$categoryGroup->category_group_id."-category_group_id"] ) !!}


                                <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='{!! $categoryGroup->name !!}_div'>
                                    <label for='{!! $categoryGroup->name !!}_field' data-test='{!! $categoryGroup->name !!}_label'>{!! $categoryGroup->name !!} categories:</label>
                                    <div class='input-group'>
                                        <span class='input-group-addon'>

                                            <input type='hidden' name='linked[{!! $index !!}]' value='0'>

                                            @if(empty($categoryGroup->is_linked) )
                                                @php $checked = '' @endphp
                                            @else
                                                @php $checked = 'checked=\'true\'' @endphp

                                            @endif

                                        <input type='checkbox' class="cb-element" name='linked[{!! $index !!}]' value='1' {!! $checked !!}  id='{!! $categoryGroup->category_group_id !!}_link_field' data-test='{!! $categoryGroup->category_group_id !!}_checkbox'>
                                        </span>

                                        <input type='text' value='{!! strtolower($categoryGroup->name) !!}' class='form-control' id='{!! $categoryGroup->name !!}_value_field' data-test='{!! $categoryGroup->name !!}_value_field'>


                                            <div class="input-group-btn">

                                                <a data-title="{!! ucwords($categoryGroup->name) !!}" data-author="{!! $categoryGroup->user->email !!}" data-description="{!! $categoryGroup->description !!}" id='{!! $categoryGroup->category_group_id !!}_info_button' data-test='{!! $categoryGroup->category_group_id !!}_info_button' class='btn @if($categoryGroup->user->id != Auth::user()->id) bg-purple @else btn-info @endif open-info-button'><i class='fa fa-info-circle'></i></a>
                                                <a href="{!! url('categories?col=1&q='.$categoryGroup->category_group_id) !!}"class="btn btn-warning show-button" data-test="show-button-0">
                                                    <i class="fa fa-tree"></i>
                                                </a>


                                            @if(Auth::user()->id === $categoryGroup->user_id || LemurPriv::isAdmin(Auth::user()))

                                                <a href="{!! url('categoryGroups/'.$categoryGroup->category_group_id.'/edit') !!}" id='{!! $categoryGroup->category_group_id !!}_edit_button' data-test='{!! $categoryGroup->category_group_id !!}_edit_button' class='btn btn-success edit-button'><i class='fa fa-edit'></i></a>

                                                @endif

                                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class='fa fa-download'></i>
                                                    <span class="fa fa-caret-down"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="{!! url('/categories/'.$categoryGroup->category_group_id.'/download/csv') !!}" type='link' id='{!! $categoryGroup->category_group_id !!}_download_csv_button' data-test='{!! $categoryGroup->category_group_id !!}_download_csv_button'>CSV</a></li>
                                                        <li><a href="{!! url('/categories/'.$categoryGroup->category_group_id.'/download/aiml') !!}" type='link' id='{!! $categoryGroup->category_group_id !!}_download_csv_button' data-test='{!! $categoryGroup->category_group_id !!}_download_csv_button'>AIML</a></li>
                                                    </ul>
                                            </div>


                                    </div>
                                    <small>id: {!! $categoryGroup->category_group_id !!}</small>
                                </div>





                                                @endforeach
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                    <div class="clearfix"></div>
                                               @endif
            @endforeach

        <!-- Submit Field -->
            <div class="form-group col-sm-12">
                {!! Form::submit('Save', ['class' => 'btn btn-primary', 'data-test' => 'save-bot-cagtegory-groups']) !!}
                <button type="reset" class="btn btn-default" data-test="reset-bot-cagtegory-groups">Reset</button>
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
                let author = $(this).attr('data-author');

                //coin field - which should be disabled in this form
                $('div#showInfoModal p#info-body').html(description);
                $('div#showInfoModal #info-author').html(author);
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
                    <small class="pull-left">Created by: <span id="info-author"></span></small>
                    <button type="button" class="btn btn-secondary" data-test="show-info-modal-close" data-dismiss="modal">Close</button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>

        var criticalItems = '{!! $bot->critical_category_group !!}'

        $('#checkall').change(function () {
            $('.cb-element').prop('checked',this.checked);
            if($('#'+criticalItems+'_link_field').length){
                $('#'+criticalItems+'_link_field').prop('checked',true).prop('disabled',true);
            }
        });

        $('.cb-element').change(function () {
            if ($('.cb-element:checked').length == $('.cb-element').length){
                $('#checkall').prop('checked',true);
            }
            else {
                $('#checkall').prop('checked',false);
            }
        });

        if(criticalItems!=''){
            $('#'+criticalItems+'_link_field').prop('checked',true).prop('disabled',true);
            text = $('#'+criticalItems+'_value_field').val();
            $('#'+criticalItems+'_value_field').prop('readonly',true).val(text+' (critical - cannot unset)');
        }

        $('#checksuggested').change(function () {
            if($('#checksuggested').is(":checked")){

                var items = {!! json_encode(config('lemurbot.dropdown.suggested_category_groups'))!!}

                for(var i = 0; i < items.length; i++) {
                    if($('#'+items[i]+'_link_field').length) {
                        $('#' + items[i] + '_link_field').prop('checked', true);
                    }
                }
            }
        });


    </script>
@endpush


