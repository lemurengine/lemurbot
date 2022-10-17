<div class="callout callout-info">
    <h4>Avatar Chat</h4>
    <p>Follow the instructions below to add the avatar chat panel onto your webpage. <a target="_new" href="https://lemurbot.com/avatar.html">Demo the avatar chat panel</a></p>
</div>
<div class="form-group col-lg-12 col-md-12 col-sm-12">
    <ul class="timeline timeline-inverse">
        <!-- timeline time label -->
        <li class="time-label">

        </li>
        <!-- /.timeline-label -->
        <!-- timeline item -->
        <li>
            <i class="fa fa-css3 bg-blue"></i>

            <div class="timeline-item">

                <h3 class="timeline-header">Add CSS stylesheet to your page</h3>

                <div class="timeline-body clearfix">
                    <p>Copy the following stylesheet tag and place it in the &lt;head>&lt;/head> tags.</p>
                    <!-- CSS Field -->
                    <div class="form-group" data-test="output_div">
                        {!! Form::label('css', 'CSS:', ['data-test'=>"css"]) !!}
                        <textarea class="form-control" rows="3"><link rel="stylesheet" href="{!! env('APP_URL') !!}/widgets/avatar/chat.css"></textarea>
                    </div>
                </div>
            </div>
        </li>
        <!-- END timeline item -->
        <!-- timeline item -->
        <li>
            <i class="fa fa-code bg-aqua"></i>

            <div class="timeline-item">
                <h3 class="timeline-header">Add the HTML tag to the body of your page</h3>

                <div class="timeline-body clearfix">
                    <p>Place this in &lt;body>&lt;/body> wherever you want the avatar chat panel to be displayed</p>
                    <!-- HTML Field -->
                    <div class="form-group" data-test="output_div">
                        {!! Form::label('html', 'HTML:', ['data-test'=>"html"]) !!}
                        <textarea class="form-control" rows="3"><!-- bot chat window -->
<div id="chat-avatar" data-botid="{!! $bot->slug !!}" data-clientid="" data-host="{!! env('APP_URL') !!}"></div>
<!--/bot chat window --></textarea>
                    </div>
                </div>
                <div class="timeline-footer">
                    <button type="button" class="btn btn-info btn-flat btn-xs" data-toggle="modal" data-target="#modal-options">
                        View Available Options
                    </button>
                </div>
            </div>
        </li>
        <!-- END timeline item -->
        <!-- timeline item -->
        <li>
            <i class="fa fa-superscript bg-yellow"></i>

            <div class="timeline-item">

                <h3 class="timeline-header">Add the JS tag to the bottom of your page</h3>

                <div class="timeline-body clearfix">
                    <p>Place this in just before the closing &lt;body> tag at the bottom of your page.</p>
                    <p>It is important to put it after jQuery.</p>
                    <div class="form-group" data-test="output_div">
                        {!! Form::label('js', 'JS:', ['data-test'=>"js"]) !!}
                        <textarea class="form-control" rows="3"><script type="text/javascript" src="{!! env('APP_URL') !!}/widgets/avatar/avataaars.js"></script>
<script type="text/javascript" src="{!! env('APP_URL') !!}/widgets/avatar/chat.js"></script></textarea>
                    </div>
                </div>
            </div>
        </li>

    </ul>
</div>
