<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">




    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>

        {!! Form::model($bot, ['route' => ['bots.update', $bot->slug], 'method' => 'patch',  'enctype' => 'multipart/form-data',  'data-test'=>$htmlTag.'-edit-form', 'class'=>'validate', 'name'=>$htmlTag.'-edit']) !!}

        @include('lemurbot::bots.fields')

        <!-- Submit Field -->
            <div class="form-group col-sm-12">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('bots.index') }}" class="btn btn-default">Cancel</a>
            </div>


        {!! Form::close() !!}



        </div>
</section>
