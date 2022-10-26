@extends('lemurbot::layouts.app')

@section('content')

    <section class="content-header">
        <h1 data-test="edit-bot-title">


            <img class="bot-image-header img-circle" alt='{!! $bot->name !!} Logo'  src='{!! asset($bot->imageUrl) !!}'></small>
            {!! $bot->name !!} <small>(id: {!! $bot->slug !!})</small>


            <div class="pull-right">

                @if($htmlTag==='bot-allowed-sites' || $htmlTag==='bot-keys' || $htmlTag==='bot-properties' || $htmlTag==='bots'|| $htmlTag==='bot-category-groups' || $htmlTag==='bots-readonly' )
                     @if($htmlTag=='bot-properties')
                        <button class="btn-sm btn btn-primary" id="openAddModal" data-test="{!! $htmlTag !!}-add-new-button"><i class="fa fa-plus"></i> Add New</button>
                        <a class="btn-sm btn btn-primary" href="{!! url("/botPropertiesUpload") !!}" data-test="{!! $htmlTag !!}-upload-button"><i class="fa fa-upload"></i> Upload</a>
                        <a class="btn-sm btn btn-primary" href="{!! url("/bot/properties/".$bot->slug."/download") !!}" data-test="{!! $htmlTag !!}-download-button"><i class="fa fa-download"></i> Download</a>
                    @elseif($htmlTag=='bot-category-groups')
                        <a class="btn-sm btn btn-primary" href="{!! url("/categories/create") !!}" data-test="{!! $htmlTag !!}-add-new-button"><i class="fa fa-plus"></i> Add New</a>
                        <a class="btn-sm btn btn-primary" href="{!! url("/categoriesUpload") !!}" data-test="{!! $htmlTag !!}-upload-button"><i class="fa fa-upload"></i> Upload Categories</a>
                    @elseif($htmlTag=='bots-readonly'||$htmlTag=='bots')
                        <a class="btn-sm btn btn-primary" href="{!! url("/bots/create") !!}" data-test="{!! $htmlTag !!}-add-new-button"><i class="fa fa-plus"></i> Add New</a>
                    @else
                        <button class="btn-sm btn btn-primary" id="openAddModal" data-test="{!! $htmlTag !!}-add-new-button"><i class="fa fa-plus"></i> Add New</button>
                    @endif

                    @if($htmlTag=='bots-readonly')
                        @php $readonly = true @endphp
                        <a class="btn-sm btn btn-info" href="{!! url('/bots/'.$bot->slug.'/edit') !!}" data-test="{!! $htmlTag !!}-edit-button"><i class="fa fa-edit"></i> Edit</a>
                    @elseif($htmlTag=='bots')
                        @php $readonly = false @endphp
                        <a class="btn-sm btn btn-info" href="{!! url('/bots/'.$bot->slug) !!}" data-test="{!! $htmlTag !!}-read-button"><i class="fa fa-book"></i> Read Only</a>
                    @endif


               @endif
              <!--
               <a class="btn btn-sm btn-info" href="">Previous</a>
               <a class="btn btn-sm btn-info" href="">Next</a>
               -->
           </div>
       </h1>

   </section>


  <div class="content">
      @include('lemurbot::layouts.feedback')
      <div class="box box-primary">
          <div class="box-body edit-page">
              @php
              $activeTab = 'tab_bot';
              @endphp
              @include('lemurbot::bots.tabs_open')
                  @if($htmlTag=='bots' || $htmlTag=='bots-readonly' )
                      <!-- bot pane -->
                      <div class="tab-pane active" id="bot-{!! $htmlTag !!}-pane" data-test="bot-{!! $htmlTag !!}-pane">

                          @if($htmlTag=='bots-readonly')
                              @include('lemurbot::bots.show_fields_by_bot')
                          @else
                              @include('lemurbot::bots.show_by_bot')
                          @endif

                          <div class="clearfix"></div>
                      </div>

                          <!-- Slug Field Edit Modal -->
                      @include('lemurbot::layouts.edit_slug_modal')



                  @else
                       <!-- {!! $htmlTag !!} pane -->
                       <div class="tab-pane active" id="bot-{!! $htmlTag !!}-pane" data-test="bot-{!! $htmlTag !!}-pane">
                       @include($resourceFolder.'.show_by_bot')
                       <div class="clearfix"></div>
                      </div>



                  @endif
                  @push('scripts')
                      {{ Html::script('vendor/lemurbot/js/validation.js') }}
                      {{ Html::script('vendor/lemurbot/js/modalSelect2.js') }}
                  @endpush
               @include('lemurbot::bots.tabs_close')
          </div>
      </div>
  </div>
@endsection
