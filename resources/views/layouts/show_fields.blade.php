@foreach($item->toArray() as $field => $value)

    @if($field === 'deleted_at')
        <div class="clearfix"><br/></div>
    @endif

    @if(in_array($field, ['deleted_at','created_at','updated_at']))
        <div class="form-group col-md-4">
            {!! Form::label($field.'_field', $field) !!}
            {!! Form::text($field.'_field', $value, ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"$htmlTag-$field-input", 'data-test'=>"$htmlTag-$field-input"]) !!}
        </div>
    @else

        @php $textArea = false @endphp

        <!-- {!! $field !!} Field -->
        @if(in_array($field,['id','coin_id']))
            <div class="form-group col-sm-6 col-md-6">
        @elseif(stripos($field,'_at')!==false ||  stripos($field,'is_')!==false || stripos($field,'_id')!==false || stripos($field,'neg')!==false || stripos($field,'days')!==false || stripos($field,'pos')!==false || stripos($field,'neu')!==false || stripos($field,'count')!==false || stripos($field,'total')!==false)
            <div class="form-group col-sm-4 col-md-2">
        @elseif(stripos($field,'url')!==false )
            <div class="form-group col-sm-6 col-md-6">
        @elseif(is_numeric($value))
            <div class="form-group col-sm-4 col-md-2">
        @elseif(json_decode($value) && json_last_error() == JSON_ERROR_NONE)
             <div class="form-group col-sm-8 col-md-10">
             @php $textArea = true @endphp
        @else
             <div class="form-group col-sm-6 col-md-6">
        @endif

            {!! Form::label($field.'_field', Illuminate\Support\Str::title(str_replace("_"," ",$field)).":") !!}

            @if($textArea && !is_numeric($value) && json_decode($value) && json_last_error() == JSON_ERROR_NONE)
                <textarea id='{!! $htmlTag !!}-{!! $field !!}-field'
                    datatest='{!! $htmlTag !!}-{!! $field !!}-field'
                    readonly='readonly'
                    rows="{!! count(json_decode($value,true))+2 !!}"
                    class='form-control'>{!! json_encode(json_decode($value,true),JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) !!}</textarea>
            @elseif($field=='id')
                     <div class="input-group">
                         <input type="text" class="form-control" value="{!! $value !!}" readonly="readonly" id="{!! $htmlTag !!}-{!! $field !!}-input" data-test="{!! $htmlTag !!}-{!! $field !!}-input">
                         <span class="input-group-btn">
                        <a class="btn btn-success" href="{!! Request::url().'/edit' !!}" type="button"><i class="fa fa-edit"></i> Edit</a>
                      </span>
                     </div><!-- /input-group -->
            @elseif($field=='coin_id')
                     <div class="input-group">
                         <input type="text" class="form-control" value="{!! $value !!}" readonly="readonly" id="{!! $htmlTag !!}-{!! $field !!}-input" data-test="{!! $htmlTag !!}-{!! $field !!}-input">
                         <span class="input-group-btn">
                        <a class="btn btn-info" href="{!! url('/coin/'.$link.'/'.$value.'/list') !!}" type="button"><i class="fa fa-forward"></i> View Coin</a>
                      </span>
                     </div><!-- /input-group -->
                 @else

                     {!! Form::text($field.'_field',  $value , ['readonly'=>'readonly', 'class' => 'form-control', 'id'=>"$htmlTag-$field-input", 'data-test'=>"$htmlTag-$field-input"]) !!}


                 @endif

            </div>

    @endif

    @if($field === 'updated_at')
        <div class="clearfix"><br/></div>
    @endif

 @endforeach

