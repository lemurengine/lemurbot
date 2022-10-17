<!-- Widget: user widget style 1 -->
<div class="box box-widget widget-user-2">
    <!-- Add the bg color to the header using any of the bg-* classes -->

    <div class="box-footer no-padding">
        <ul class="nav nav-stacked">




                <div class="box-body">
                    <ul class="products-list product-list-in-box">

                    @foreach($conversations as $index => $item)




                        <!-- /.item -->
                            <li class="item @if($targetConversationSlug === $item->slug) selected-conversation @endif">
                                <div class="col-md-12">
                                    <a href="{!! url('/bot/logs/'.$bot->slug.'/'.$item->slug) !!}" class="product-title">{!! $item->slug !!}
                                        <span class="label label-success pull-right">{!! $item->turnCount !!}</span></a>
                                        <span class="product-description">
                                            {!! Carbon\Carbon::parse($item->updated_at)->diffForHumans(); !!}
                                        </span>
                                </div>
                            </li>
                            <!-- /.item -->




                        @endforeach
                    </ul>
                </div>

        </ul>
    </div>
</div>
<!-- /.widget-user -->
