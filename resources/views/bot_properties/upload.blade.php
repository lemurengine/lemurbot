@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Upload Bot Properties
        </h1>
    </section>
    <div class="content">
        @include('lemurbot::layouts.feedback')
        <div class="box box-primary">
            <div class="box-body add-page">
                <div class="row">
                    <div class="col-md-12">

                        <div class="col-md-12">
                            <h4>Format:</h4>
                            <p>The format of your csv file should be:</p>
                            <code>"BotId","Name","Value"<br/>
                                "dilly","ethics","I am always trying to stop fights"<br/>
                                "dilly","favoritedj","Derrick Carter"<br/>
                            </code>
                            <br/>
                        </div>
                        <div class="col-md-12">
                            <h4>Prerequisites:</h4>
                            <ul>
                                <li>Make sure that there your bot exists - created by you.</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <h4>Upload Rules:</h4>
                            <ul>
                                <li>The format of the file should be a comma separated csv file</li>
                                <li>The first row should will be ignored (as assume this contains column headings</li>
                                <li>Any columns after the ones listed above will be ignored</li>
                                <li>If the bot id does not exist you will not be able to upload the records</li>
                                <li>No changes will be made if there are any errors when processing the file</li>
                                <li>If you want an example of the file, then just download a file from the table page</li>
                            </ul>
                            <br/>
                        </div>

                        <div class="col-md-12">
                            <h4>Select File:</h4>
                            {!! Form::open(['url' => 'botPropertiesUpload', 'data-test'=>$htmlTag.'-upload-form', 'class'=>'validate', 'name'=>$htmlTag.'-upload', 'enctype'=>"multipart/form-data"]) !!}

                            <div class="form-group col-sm-6 col-lg-6">
                                {!! Form::label('file', 'File:') !!}
                                <div class="input-group">
                                    <label class="input-group-btn">
                                            <span class="btn btn-primary">
                                            Browse… <input name="upload_file" type="file" style="display: none;" data-test="{!! $htmlTag !!}-file-button" id="{!! $htmlTag !!}-file-button" data-validation='required'>
                                        </span>
                                    </label>
                                    <input type="text" class="form-control" readonly="" id="{!! $htmlTag !!}-file-input" data-test="{!! $htmlTag !!}-file-input">
                                </div>
                                <span id="slug-info-message" class="help-block form-info">Select a file from your computer to upload</span>
                            </div>
                            <br/>
                        </div>
                        <div class="col-md-12">
                            <h4>Processing Options:</h4>
                            <div class="form-group col-sm-12 col-lg-12">

                                <div class="radio">
                                    <label>
                                        <input type="radio" name="processingOptions" id="processingOptions_a" value="append" checked="">
                                        Add the new records and update existing properties
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="processingOptions" id="processingOptions_b" value="delete">
                                        Delete all existing bot properties and create new
                                    </label>
                                </div>
                            </div>

                            <div class="clearfix"><br/></div>
                            <br/>
                            <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Upload', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route('botProperties.index') }}" class="btn btn-default">Cancel</a>
                            </div>


                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{ Html::script('vendor/lemurbot/js/validation.js') }}
    {{ Html::script('vendor/lemurbot/js/select2.js') }}



    <script>
        $(document).on('change', ':file', function() {
            var input = $(this),
                numFiles = input.get(0).files ? input.get(0).files.length : 1,
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });

        $(document).ready( function() {
            $(':file').on('fileselect', function(event, numFiles, label) {
                console.log(numFiles);
                console.log(label);
                $('input#{!! $htmlTag !!}-file-input').val(label)
            });
        });
    </script>
@endpush
