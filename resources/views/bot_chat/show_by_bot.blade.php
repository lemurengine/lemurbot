<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>





        <div class="row">

            <div class="col-md-4">
                <!-- DIRECT CHAT PRIMARY -->
                <div class="box box-primary direct-chat direct-chat-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title  pull-left  col-md-8">Direct Chat</h3>


                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- Conversations are loaded here -->
                        <div class="direct-chat-messages" id="direct-chat-messages">
                            <!-- Message. Default to the left -->
                        @if((($conversation != null) && count($conversation->turns->take(10))>0))

                            @php $botImage = $bot->imageUrl; @endphp

                            @foreach($conversation->conversationHumanLogs as $index => $item)

                                <!-- Message. Default to the left -->
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-left">{!! $conversation->client->slug !!}</span>
                                            <span class="direct-chat-timestamp pull-right">{!! $item->created_at !!}</span>
                                        </div>
                                        <!-- /.direct-chat-info -->
                                        <img class="direct-chat-img" src="{!! Avatar::create($conversation->client->slug)->toBase64() !!}" alt="Message User Image"><!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            {!! $item->input !!}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->

                                    <!-- Message to the right -->
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-right">{!! $bot->name !!}</span>
                                            <span class="direct-chat-timestamp pull-left">{!! $item->created_at !!}</span>
                                        </div>
                                        <!-- /.direct-chat-info -->
                                        <img class="img-circle direct-chat-img" src="{!! $botImage !!}" alt="Message Bot Image"><!-- /.direct-chat-img -->
                                        <div class="direct-chat-text">
                                            {!! $item->output !!}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>



                            @endforeach

                        @endif
                            <!-- /.direct-chat-msg -->
                        </div>
                        <!--/.direct-chat-messages-->
                        <!-- /.direct-chat-pane -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <form action="/bot/{!! $bot->slug !!}/chat" method="POST" id="chat-form">
                        @csrf

                        @if(!empty($response) && !empty($response['client']['id']))

                            {{ Form::hidden('client', $response['client']['id']) }}
                        @else
                            {{ Form::hidden('client', MD5(Auth::user()->api_token)) }}
                        @endif

                            <div class="input-group">
                                <input type="hidden" name="bot" id="bot" value="{!! $bot->slug !!}">
                                <input type="hidden" name="html" id="html" value="1">
                                <input type="hidden" name="redirect_url" id="redirect_url" value="{!! url('/bot/'.$bot->slug.'/chat') !!}">
                                <input type="text" name="message" id="message" placeholder="Type Message ..." class="form-control">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-flat">Send</button>
                                </span>
                            </div>
                            <span id="convo-help-message" class="help-block form-info">Type: 'start a new conversation' to start again</span>

                        </form>
                    </div>
                    <!-- /.box-footer-->
                </div>
                <!--/.direct-chat -->
            </div>
            <!-- /.col -->

            <div class="col-md-8">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user-2">

                    <div class="box-body">

                        @if(Auth::user()->id === $bot->user_id || LemurPriv::isAdmin(Auth::user()) )


                        <div class="nav-tabs-custom">
                            <!-- response tabs --->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#response" data-toggle="tab">Response</a></li>
                                <li><a href="#debug" data-toggle="tab">Debug</a></li>
                                <li><a href="#wildcards" data-toggle="tab">Wildcards</a></li>
                                <li><a href="#globals" data-toggle="tab">Globals</a></li>
                                <li><a href="#locals" data-toggle="tab">Locals</a></li>
                                @if(LemurPriv::isAdmin(Auth::user()))
                                <li><a href="#admin" data-toggle="tab">Admin</a></li>
                                @endif

                                @if(!empty($sentences))
                                    <li><a href="#sentence-debug" data-toggle="tab">Sentences Debug</a></li>
                                @endif
                                @if( !empty($response) && !empty($bot) && !empty($conversation))
                                    <li><a href="#chat-plain" data-toggle="tab">Plain Chat</a></li>
                                    <li><a href="{!! url('/bot/logs/'.$bot->slug.'/'.$conversation->slug.'/download') !!}">Download</a></li>
                                @endif

                            </ul>
                            <div class="tab-content">
                                <div class="active tab-pane" id="response">

                                    @if(!empty($response))
                                        <pre><code>{{ json_encode($response,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="alert alert-info">Start talking and full response will appear here</div>
                                    @endif

                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="debug">
                                    @if(!empty($debug['debug']))
                                        @if(!empty($sentences['debugArr']))
                                            <div class="alert alert-info">This interaction is made up for more than one sentence. These are the current wildcards.</div>
                                        @endif

                                        <pre><code>{{ json_encode($debug['debug'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="alert alert-info">Start talking and detailed debug information will appear here</div>
                                    @endif

                                </div>

                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="wildcards">
                                    @if(!empty($debug['wildcards']))
                                        @if(!empty($sentences['debugArr']))
                                            <div class="alert alert-info">This interaction is made up for more than one sentence. This the debug array for the last sentence.</div>
                                        @endif
                                        <pre><code>{{ json_encode($debug['wildcards'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="alert alert-info">No wildcards</div>
                                    @endif

                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="globals">
                                    @if(!empty($debug['globals']))
                                        @if(!empty($sentences['debugArr']))
                                            <div class="alert alert-info">This interaction is made up for more than one sentence. These are the current global vars.</div>
                                        @endif
                                        <pre><code>{{ json_encode($debug['globals'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="alert alert-info">No global variables</div>
                                    @endif

                                </div>
                                <!-- /.tab-pane -->


                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="locals">
                                    @if(!empty($debug['locals']))
                                        @if(!empty($sentences['debugArr']))
                                            <div class="alert alert-info">This interaction is made up for more than one sentence. This the local vars array for the last sentence.</div>
                                        @endif
                                        <pre><code>{{ json_encode($debug['locals'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="alert alert-info">No local variables</div>
                                    @endif

                                </div>
                                <!-- /.tab-pane -->


                            @if(LemurPriv::isAdmin(Auth::user()))
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="admin">
                                    @if(!empty($debug['admin']))

                                        @if(!empty($sentences['debugArr']))
                                            <div class="alert alert-info">This interaction is made up for more than one sentence. This the debug array for the last sentence.</div>
                                        @endif
                                        <pre><code>{{ json_encode($debug['admin'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                    @else
                                        <div class="alert alert-info">No admin debug</div>
                                    @endif

                                </div>
                                <!-- /.tab-pane -->
                                @endif

                            @if(!empty($response) && !empty($conversation))
                                <!-- /.tab-pane -->

                                    <div class="tab-pane" id="chat-plain">
<pre><code>
@foreach($conversation->conversationHumanLogs as $index => $item)<span class="text-wrapped">User: {!! $item->input !!}</span>
<span class="text-muted-wrapped">Bot: {!! $item->output !!}</span>

@endforeach
</code></pre>
                                    </div>


                                    <!-- /.tab-pane -->
                            @endif

                                @if(!empty($sentences))
                                    <!-- /.tab-pane -->

                                        <div class="tab-pane" id="sentence-debug">
                                            @if(!empty($sentences['debugArr']))
                                                <div class="alert alert-info">This interaction is made up for more than one sentence. This the debug array for all the sentence.</div>
                                                <pre><code>{{ json_encode($sentences['debugArr'],JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                                            @else
                                                <div class="alert alert-info">No sentence debug</div>
                                            @endif

                                        </div>

                                        <!-- /.tab-pane -->
                                @endif

                            </div>
                            <!-- /.tab-content -->
                        </div>


                        @else

                            <div class="alert alert-info">Only the owner of this bot can see detailed debug data.</div>

                        @endif
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
        </div>

    </div>
    <!-- /.content -->
</section>

@push('scripts')
    {{ Html::script('vendor/lemurbot/js/select2.js') }}
<script>

    $( "#select-bot-id" ).change(function(){
        $("form#chat-form").submit();
    });

    var element = document.getElementById('direct-chat-messages');
    element.scrollTop = element.scrollHeight - element.clientHeight;
    $( "#message" ).focus();
</script>
@endpush


