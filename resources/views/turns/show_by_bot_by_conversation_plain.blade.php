@if((($fullConversation == null) || count($fullConversation->turns)<=0))

    <div class="alert alert-info">There are no {!! strtolower($title) !!} associated with this bot </div>

@else


    <!-- Conversations are loaded here -->
    <div class="direct-chat-messages">
    @foreach($fullConversation->conversationHumanLogs as $index => $item)





            <!-- Message to the right -->
            <div class="direct-chat-msg">

                <span class="text-wrapped">User: {!! $item->input !!}</span>
                <br/>
                <span class="text-muted-wrapped">Bot: {!! $item->output !!}</span>
            </div>
            <!-- /.direct-chat-msg -->






    @endforeach

    </div>
    <div class="clearfix"></div>
    <!--/.direct-chat-messages-->
@endif
