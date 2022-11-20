<aside class="main-sidebar" id="sidebar-wrapper">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{!! Avatar::create(Auth::user()->name)->toBase64() !!}"
                     class="user-image" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p data-test="sidebar-user-name">{{ Auth::user()->name}}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ (LemurPriv::isAdmin(Auth::user())?'Admin':'Member')}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <form action="{!! url('/quickchat') !!}" method="get" class="sidebar-form">


            <!-- Category Group Id Field -->
                <div class="form-group col-sm-12 select2" data-test="category_group_id_div">
                    {!! Form::label('bot_id', 'Bot:', ['data-test'=>"bot-id-quick-chat-label"]) !!}
                    <div class="input-group">
                        {!! Form::select('bot_id', LemurEngine\LemurBot\Models\Bot::myBots(), Session::get('target_bot')->slug??"", [ 'placeholder'=>'Quick Chat...', 'class' => 'form-control select2', 'data-test'=>"bot-id-quick-chat-select", 'id'=>"bot-id-quick-chat-select"]) !!}
                        <div class="input-group-btn">
                            @if(!empty(Session::get('target_bot')->slug)))
                                <button type="submit" id="bot-id-quick-chat-go-button" class="btn btn btn-info" data-test='bot-id-quick-chat-go-button'>
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                                <button type="button" id='bot-id-quick-chat-popup-button' class="btn btn-warning" data-test='bot-id-quick-chat-popup-button'>
                                    <i class="fa fa-arrow-up"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            <!--/.direct-chat-messages-->
                <div class="clearfix"></div>





        </form>
        <ul class="sidebar-menu" data-widget="tree">
            @include('lemurbot::layouts.menu')
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
@push('scripts')
<script>
    $(document).ready(function() {
        $("#bot-id-quick-chat-popup-button").click(function (e) {
            var botName = $('#bot-id-quick-chat-select :selected').text();
            var botId = $('#bot-id-quick-chat-select :selected').val();
            var left = (screen.width ) ;
            var top = (screen.height ) ;
            window.open('/bot/'+botId+'/popup','Chatting with '+botName,'toolbar=no, menubar=no, resizable=no, width=400,height=500 , top=' + top + ', left=' + left);
        });
    });

    //window.open(url,'window','toolbar=no, menubar=no, resizable=yes');
</script>
@endpush
