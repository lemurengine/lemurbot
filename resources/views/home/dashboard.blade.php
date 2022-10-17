
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Your Bots
            </h1>
        </section>
        <!-- Main content -->
        <section data-masonry='{"percentPosition": true }'>

            @php $index = 0; @endphp

            @if(empty($authorBots->first()))
                <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="callout callout-info">Create your first bot by <a href="{!! url('/bots/create') !!}">clicking here</a>.</div>
                </div>
            @else

                @foreach($authorBots as $bot)


                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="thumbnail">
                                <span class=""><img class="profile-user-img img-responsive img-circle pull-left" src="{!! $bot->imageUrl !!}" alt="Bot Avatar"></span>
                                <div class="home-info-box-content">
                                    <span class="info-box-text">{!! $bot->name !!}</span>
                                    <span class="info-box-number" style="word-break: break-word">{!! $bot->summary !!}<br/></span>
                                    <span class="text-muted-wrapped">{!! $bot->conversationTurnsLast28Days->count() !!} interactions in last 28 days</span>
                                </div>
                                <!-- /.info-box-content -->
                                <div class="">
                                    <div class="btn-group btn-group-sm bot-commands" role="group" aria-label="Bot Commands">

                                        @if($bot->user_id !== Auth::user()->id )
                                            <button type="button" class="btn btn-default open-chat" data-chatbot="{!! $bot->slug !!}" data-target="#modal-chat">
                                                Chat
                                            </button>
                                        @else
                                            <a href="{!! url('/bot/'.$bot->slug.'/chat') !!}" class="btn btn-sm btn-default">Chat</a>
                                            <a href="{!! url('/bots/'.$bot->slug.'/edit') !!}" class="btn btn-sm btn-info">Edit</a>
                                            <a href="{!! url('/bot/logs/'.$bot->slug.'/list') !!}" class="btn btn-sm btn-warning">Logs</a>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>



                    @php $index++; @endphp

                @endforeach

            @endif
        </section>
        <div class="clearfix"></div>
        @if(!empty($publicBots->first()))

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    Public Bots
                </h1>
            </section>
            <!-- Main content -->
            <section data-masonry='{"percentPosition": true }'>

                @php $index = 0; @endphp
                @foreach($publicBots as $bot)


                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="thumbnail">
                            <span class=""><img class="profile-user-img img-responsive img-circle pull-left" src="{!! $bot->imageUrl !!}" alt="Bot avatar"></span>
                            <div class="home-info-box-content">
                                <span class="info-box-text">{!! $bot->name !!}</span>
                                <span class="info-box-number" style="word-break: break-word">{!! $bot->summary !!}<br/></span>
                                <span class="text-muted-wrapped">{!! $bot->conversationTurnsLast28Days->count() !!} interactions in last 28 days</span>
                            </div>
                            <!-- /.info-box-content -->
                            <div class="">
                                <div class="btn-group btn-group-sm bot-commands" role="group" aria-label="Bot Commands">

                                    @if($bot->user_id !== Auth::user()->id )
                                        <button type="button" class="btn btn-default open-chat" data-chatbot="{!! $bot->slug !!}" data-target="#modal-chat">
                                            Chat
                                        </button>
                                    @else
                                        <a href="{!! url('/bot/'.$bot->slug.'/chat') !!}" class="btn btn-sm btn-default">Chat</a>
                                        <a href="{!! url('/bots/'.$bot->slug.'/edit') !!}" class="btn btn-sm btn-info">Edit</a>
                                        <a href="{!! url('/bot/logs/'.$bot->slug.'/list') !!}" class="btn btn-sm btn-warning">Logs</a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    @php $index++; @endphp

                @endforeach

            </section>

            <div class="clearfix"></div>

        @endif
        <div class="modal" id="modal-chat" style="display: none;">
            <div class="modal-sm modal-dialog">
                <div class="modal-content">

                    <div class="box box-primary direct-chat direct-chat-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Chat</h3>

                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Conversations are loaded here -->
                            <div id="direct-chat-window" class="direct-chat-messages">

                            </div>
                            <!--/.direct-chat-messages-->


                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <form action="#" method="post" id="chat-form">
                                <div class="input-group">
                                    <input type="hidden" name="bot" id="bot" value="">
                                    <input type="hidden" name="client" id="client" value="{!! Auth::user()->slug !!}">
                                    <input type="text" name="message" id="message" placeholder="Type Message ..." class="form-control">
                                    <span class="input-group-btn">
                                        <button type="submit" id="submit-button" class="btn btn-primary btn-flat">Send</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <!-- /.box-footer-->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


 
        <!-- /.content -->

        @push('scripts')


            {{ Html::script('https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js') }}
            <script>
                $( document ).ready(function() {

                    $('.open-chat').click(function(){
                        $('#bot').val($(this).attr('data-chatbot'))
                        $('#direct-chat-window').html('');
                        $('#modal-chat').modal('show');
                    })


                    $('#chat-form').submit(function(e){
                        e.preventDefault();

                        $( "#submit-button" ).prop( "disabled", true );

                        let userHtml = '<!-- Message. Default to the left -->\n' +
                            '        <div class="direct-chat-msg">\n' +
                            '            <div class="direct-chat-info clearfix">\n' +
                            '                <span class="direct-chat-name pull-left">{!! Auth::user()->name !!}</span>\n' +
                            '                <span class="direct-chat-timestamp pull-right">{!! Carbon\Carbon::now() !!}</span>\n' +
                            '            </div>\n' +
                            '            <!-- /.direct-chat-info -->\n' +
                            '            <img class="direct-chat-img" src="{!! url('/widgets/user.png') !!}" alt="Message User Image"><!-- /.direct-chat-img -->\n' +
                            '            <div class="direct-chat-text">\n' +
                                            $('#message').val() +
                            '            </div>\n' +
                            '            <!-- /.direct-chat-text -->\n' +
                            '        </div>\n' +
                            '        <!-- /.direct-chat-msg -->'

                        $('#direct-chat-window').append(userHtml);

                        var settings = {
                            "url": '/api/talk/bot',
                            "method": "POST",
                            "timeout": 0,
                            "headers": {
                                "Content-Type": "application/json"
                            },
                            "data": JSON.stringify({"client":$('#client').val(),"bot":$('#bot').val(),"message":$('#message').val()}),
                        };

                        $.ajax(settings).done(function (response) {

                            $('#message').val('');
                            console.log(response)

                            let botHtml = '<!-- Message to the right -->\n' +
                                '        <div class="direct-chat-msg right">\n' +
                                '            <div class="direct-chat-info clearfix">\n' +
                                '                <span class="direct-chat-name pull-right">'+response.data.bot.id+'</span>\n' +
                                '                <span class="direct-chat-timestamp pull-left">{!! Carbon\Carbon::now() !!}</span>\n' +
                                '            </div>\n' +
                                '            <!-- /.direct-chat-info -->\n' +
                                '            <img class="direct-chat-img" src="'+response.data.bot.image+'" alt="Bot Image"><!-- /.direct-chat-img -->\n' +
                                '            <div class="direct-chat-text">\n' +
                                                response.data.conversation.output                 +
                                '            </div>\n' +
                                '            <!-- /.direct-chat-text -->\n' +
                                '        </div>\n' +
                                '        <!-- /.direct-chat-msg -->';

                            $('#direct-chat-window').append(botHtml);
                            $("#direct-chat-window").scrollTop($("#direct-chat-window")[0].scrollHeight);
                            $( "#message" ).focus();
                            $( "#submit-button" ).prop( "disabled", false );

                        });
                    })


                });

            </script>
        @endpush






