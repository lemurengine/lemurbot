<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>

@if(count($conversationSources)>0)


    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">
                <tbody><tr>
                    <th>User</th>
                    <th>IP</th>
                    <th>User Agent</th>
                    <th>Referer</th>
                </tr>
                @foreach($conversationSources as $source)
                    <tr>
                        <td>{!! $source->user !!}</td>
                        <td>{!! $source->ip !!}</td>
                        <td>{!! $source->user_agent !!}</td>
                        <td>{!! $source->referer !!}</td>
                    </tr>
                @endforeach

                </tbody></table>
        </div>
    </div>


@else
    <div class="col-md-12">
        <div class="alert alert-info">There are no sources associated with this conversation </div>
    </div>
@endif

    </div>
</section>
