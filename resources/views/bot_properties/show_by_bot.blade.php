<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>



        @if(count($botProperties)<=0)

            <div class="alert alert-info">There are no {!! strtolower($title) !!} associated with this bot </div>

        @else

            <!-- loop through all the sections in their 'order' and then populate with the items which exist for them -->
            @foreach($allSections as $sectionId => $sectionGroup)


            @if(!empty($botProperties[$sectionGroup->slug]))

                @php $sectionName = $sectionGroup['name']; @endphp
                @php $sectionSlug = $sectionGroup['slug']; @endphp
                    @if($sectionGroup['default_state'] == 'open')
                        @php $sectionShow = 'true'; @endphp
                        @php $collapseShow = 'collapse in'; @endphp
                    @else
                        @php $sectionShow = 'false'; @endphp
                        @php $collapseShow = 'collapse'; @endphp
                    @endif
                    <!--open the previous collaspe box-->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{!! $sectionName !!} Section</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-toggle="collapse" href="#{!! $sectionSlug !!}" role="button" aria-expanded="{!! $sectionShow !!}" aria-controls="{!! $sectionSlug !!}"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body {!! $collapseShow !!}" id="{!! $sectionSlug !!}" aria-expanded="{!! $sectionShow !!}">



                        @foreach($botProperties[$sectionSlug] as $name => $item)

                                {!! Form::open(['route' => 'botProperties.store', 'data-test'=>$htmlTag.'-create-form', 'class'=>'validate', 'name'=>$htmlTag.'-create']) !!}
                                {!! Form::hidden('bot_id', $bot->slug,['data-test'=>$htmlTag."-bot_id"] ) !!}
                                {!! Form::hidden('section_id', $sectionSlug ) !!}
                                {!! Form::hidden('redirect_url', url()->current(),['data-test'=>"$htmlTag-redirect-url"] ) !!}
                                {!! Form::hidden('name', $name ) !!}

                                <div class='form-group col-md-4 col-sm-6 col-xs-12' data-test='{!! $name !!}_div'>
                                    <label for='{!! $name !!}_field' data-test='{!! $name !!}_label'>{!! $name !!}:</label>
                                    <div class='input-group'>

                                        <input type='text' name='value' value='{!! $item['value'] !!}' class='form-control' id='{!! $name !!}_value_field' data-test='{!! $name !!}_value_field' data-validation="required">
                                        <div class="input-group-btn">
                                            <button name="edit" class="btn btn-info"><i class="glyphicon glyphicon-edit"></i> Save</button>
                                            <a class='btn btn-danger delete-button openDeleteDataTableModal' data-id="{!! $item['slug'] !!}"  data-message="Are you sure you want to delete the {!! $item['name']  !!} property? ID: {!! $item['slug']  !!}" data-test="delete-button">
                                                <i class="glyphicon glyphicon-trash"></i>
                                            </a>
                                            <button type="reset" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i> Reset</button>
                                        </div>

                                    </div>
                                </div>


                                {!! Form::close() !!}

                        @endforeach
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                @endif
    @endforeach
@endif
</div>
</section>

@include('lemurbot::layouts.by_bot_add_modal')
@include('lemurbot::layouts.datatable_delete_modal')
