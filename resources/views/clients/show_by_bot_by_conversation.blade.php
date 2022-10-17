<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>

@if(!empty($client))

    <!-- Slug Field -->
        <div class="form-group">
            ClientId:
            <p>{{ $client->slug }}</p>
        </div>

        <!-- Slug Field -->
        <div class="form-group">
            Banned?
            <p>
                @if(empty($client) || $client->is_banned==0 || !$client->is_banned)
                    No
                @else
                   Yes
                @endif


            </p>
        </div>

        @php  $htmlTag = 'clients'; @endphp
        @if(empty($client) || $client->is_banned==0 || !$client->is_banned)
            <a class='btn btn-danger btn-sm ban-button openBanDataTableModal' data-id="{!! $client->slug !!}"  data-message="Client ID: {!! $client->slug !!}" data-test="ban-button">
                <i class="fa fa-ban"></i> Ban Client
            </a>

            @include('lemurbot::clients.datatable_ban_modal')
        @else
            <a class='btn btn-success btn-sm unban-button openUnBanDataTableModal' data-id="{!! $client->slug !!}"  data-message="Client ID: {!! $client->slug !!}" data-test="unban-button">
                <i class="fa fa-ban"></i> Enable Client
            </a>
            @include('lemurbot::clients.datatable_unban_modal')
        @endif




@else
    <div class="col-md-12">
        <div class="alert alert-info">There is no client associated with this conversation </div>
    </div>
@endif

    </div>
</section>

