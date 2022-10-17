<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>

@if(count($conversationProperties)>0)


    <div class="box">

        <!-- /.box-header -->
        <div class="box-body">
            <table class="table table-bordered">
                <tbody><tr>
                    <th style="width: 10px">#</th>
                    <th>Property</th>
                    <th>Value</th>
                </tr>
                @php $index=0; @endphp
                @foreach($conversationProperties as $prop => $value)

                    @php $index++; @endphp

                    <tr>
                        <td>{!! $index !!}</td>
                        <td>{!! $prop !!}</td>
                        <td>{!! $value !!}</td>
                    </tr>


                @endforeach
                </tbody></table>
        </div>
    </div>


@else
    <div class="col-md-12">
        <div class="alert alert-info">There are no properties associated with this conversation </div>
    </div>
@endif

    </div>
</section>
