<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>
            <div class="callout callout-warning">
                <h4>It is a lot better if you use an http client and make authenticated requests to your bot using a <a href="{!! url('/bot/keys/'.$bot->slug.'/list') !!}">bot key</a>.</h4>
                <p>Only use the widget below if your bot is set to 'public' and you understand the risk associated with anyone being able to talk to your bot.</p>
            </div>




        <div class="nav-tabs-custom">
            <!-- response tabs --->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#popup" data-toggle="tab">Popup Widget</a></li>
                <li><a href="#avatar" data-toggle="tab">Avatar Widget</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="popup">

                   @include('lemurbot::bot_widgets.popup_instructions')

                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="avatar">
                    @include('lemurbot::bot_widgets.avatar_instructions')

                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>







        <div class="modal" id="modal-options" style="display: none;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Default Modal</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box-body">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Parameter</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Default</th>
                                    <th>Required?</th>
                                    <th>Example</th>
                                </tr>
                                <tr>
                                    <th>1.</th>
                                    <td>data-botid</td>
                                    <td>string</td>
                                    <td>The unique id of the bot you want to talk to</td>
                                    <td>n/a</td>
                                    <td>Yes</td>
                                    <td>{!! $bot->slug !!}</td>
                                </tr>
                                <tr>
                                    <th>2.</th>
                                    <td>data-clientid</td>
                                    <td>string</td>
                                    <td>The unique id of the client</td>
                                    <td>If you do not assign a client id then one will be assigned</td>
                                    <td>No</td>
                                    <td>{!! uniqid('',false) !!}</td>
                                </tr>
                                <tr>
                                    <th>3.</th>
                                    <td>data-host</td>
                                    <td>url</td>
                                    <td>The host site</td>
                                    <td>/ (If you do not assign one then we will assume the api is hosted on the same server as this bot chatbot)</td>
                                    <td>No</td>
                                    <td>{!! env('APP_URL'); !!}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>




        </div>
</section>
