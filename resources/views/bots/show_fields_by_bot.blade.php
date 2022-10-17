<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>

        {!! Form::model($bot) !!}

        @include('lemurbot::bots.fields')

        {!! Form::close() !!}


        <div class="form-group col-sm-12" data-test="see_all_div">
            <a href="{{ route('bots.index') }}" class="btn btn-default">See All</a>
        </div>
        </div>
</section>
