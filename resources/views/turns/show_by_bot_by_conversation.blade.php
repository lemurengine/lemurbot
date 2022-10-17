@if((($fullConversation == null) || count($fullConversation->turns)<=0))

    <div class="alert alert-info">There are no {!! strtolower($title) !!} associated with this bot </div>

@else

    @php $botImageUrl  = $bot->imageUrl; @endphp

    <!-- Conversations are loaded here -->
    <div class="direct-chat-messages">
    @foreach($fullConversation->conversationHumanLogs as $index => $item)





            <!-- Message to the right -->
            <div class="direct-chat-msg right">
                <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-right">{!! $fullConversation->client->slug !!}</span>
                    <span class="pull-left">
                        @if(!empty($item->category->slug))
                            <a href="{!! url('/categories/'.$item->category->slug) !!}">aiml</a> ::
                        @endif
                        <ul id="log-actions">

                        @if(!empty($item->slug))

                                    <li><a href="{!! url('/turns/'.$item->slug) !!}">log</a>&nbsp;::&nbsp;</li>
                                    <li><a href="{!! url('/category/fromTurn/'.$item->slug) !!}">create</a>&nbsp;::&nbsp;</li>
                                    <li>
                                            <form action="/bot/{!! $bot->slug !!}/chat" method="POST" id="chat-form">
                                                @csrf
                                                {{ Form::hidden('client', MD5(Auth::user()->api_token)) }}
                                                {{ Form::hidden('bot', $bot->slug) }}
                                                {{ Form::hidden('html', 1) }}
                                                {{ Form::hidden('redirect_url',url()->current()) }}
                                                {{ Form::hidden('message', $item->input)  }}
                                                <input type="submit" value="say" class="link-button">&nbsp;::&nbsp;
                                            </form>
                                    </li>

                        @endif
                        <li class="direct-chat-timestamp">{!! Carbon\Carbon::parse($item->created_at)->format('d/m/y h:m:sA'); !!}</li>
                                </ul>
                    </span>
                </div>
                <!-- /.direct-chat-info -->
                <img class="direct-chat-img" src="{!! Avatar::create($fullConversation->client->slug)->toBase64() !!}" alt="message user image">
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text">{!! $item->input !!}</div>
                <!-- /.direct-chat-text -->
            </div>
            <!-- /.direct-chat-msg -->

            <!-- Message. Default to the left -->
            <div class="direct-chat-msg">
                <div class="direct-chat-info clearfix">
                    <span class="direct-chat-name pull-left">{!! $bot->name !!}</span>
                    <span class="direct-chat-timestamp pull-right">{!! Carbon\Carbon::parse($item->updated_at)->format('d M Y H:m:sA'); !!}</span>
                </div>
                <!-- /.direct-chat-info -->
                <img class="direct-chat-img" src="{!! $botImageUrl !!}" alt="message user image">
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text">
                    {!! $item->output !!}
                </div>
                <!-- /.direct-chat-text -->
            </div>
            <!-- /.direct-chat-msg -->





    @endforeach

    </div>
    <div class="clearfix"></div>
    <!--/.direct-chat-messages-->
@endif
