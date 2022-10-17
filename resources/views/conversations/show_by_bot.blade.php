<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>

        @if(count($conversations)<=0)

            <div class="alert alert-info">There are no {!! strtolower($title) !!} for this bot </div>

        @else

            <div class="col-md-4">

                <div class="box box-warning direct-chat direct-chat-warning">
                    <div class="box-header with-border">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item active">
                                <a class="nav-link small" id="pills-chat-recent" data-toggle="pill" href="#pills-recent" role="tab" aria-controls="pills-recent" aria-selected="true">Most Recent 20</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link small" id="pills-chat-all"  href="{!! url('/conversations') !!}" role="tab" aria-controls="pills-chat-all" aria-selected="false">All</a>
                            </li>
                        </ul>

                    </div>
                    <div class="box-body">
                        <!-- /.box-header -->
                        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                            <div class="tab-pane fade active in" id="pills-list" role="tabpanel" aria-labelledby="nav-home-tab">
                                @include('lemurbot::conversations.show_by_bot_conversation_list')
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>

            </div>

        <div class="col-md-8">

            <div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-header with-border">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item active">
                            <a class="nav-link small" id="pills-chat-tab" data-toggle="pill" href="#pills-chat" role="tab" aria-controls="pills-chat" aria-selected="true">Chat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link small" id="pills-chat-plain-tab" data-toggle="pill" href="#pills-plain" role="tab" aria-controls="pills-plain" aria-selected="false">Plain Chat</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link small" id="pills-conversation-properties-tab" data-toggle="pill" href="#pills-conversation-properties" role="tab" aria-controls="pills-conversation-properties" aria-selected="false">Conversation Properties</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link small" id="pills-conversation-source-tab" data-toggle="pill" href="#pills-conversation-sources" role="tab" aria-controls="pills-conversation-sources" aria-selected="false">Conversation Sources</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link small" id="pills-conversation-client-tab" data-toggle="pill" href="#pills-conversation-client" role="tab" aria-controls="pills-conversation-client" aria-selected="false">Client</a>
                        </li>

                        <li class="nav-item">
                        <a class="nav-link small" id="pills-conversation-download-link"  href="{!! url('/bot/logs/'.$bot->slug.'/'.$targetConversationSlug."/download") !!}" role="tab" aria-controls="pills-conversation-download-tab" aria-selected="false">Download Stats</a>
                        </li>
                    </ul>

                </div>
                <div class="box-body">
                    <!-- /.box-header -->
                    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                        <div class="tab-pane fade active in" id="pills-chat" role="tabpanel" aria-labelledby="pills-chat">
                            @include('lemurbot::turns.show_by_bot_by_conversation')
                        </div>
                        <div class="tab-pane fade" id="pills-plain" role="tabpanel" aria-labelledby="pills-plain">
                            @include('lemurbot::turns.show_by_bot_by_conversation_plain')
                        </div>
                        <div class="tab-pane fade" id="pills-conversation-properties" role="tabpanel" aria-labelledby="pills-conversation-properties">
                            @include('lemurbot::conversation_properties.show_by_bot_by_conversation')
                        </div>
                        <div class="tab-pane fade" id="pills-conversation-sources" role="tabpanel" aria-labelledby="pills-conversation-sources">
                            @include('lemurbot::conversation_sources.show_by_bot_by_conversation')
                        </div>
                        <div class="tab-pane fade" id="pills-conversation-client" role="tabpanel" aria-labelledby="pills-conversation-client">
                            @include('lemurbot::clients.show_by_bot_by_conversation')
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>



        </div>




        @endif



    </div>
</section>

