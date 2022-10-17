@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Upload Categories</h1>
    </section>
    <div class="clearfix"></div>
    <div class="content">

@include('lemurbot::layouts.feedback')
        <div class="box box-primary">
            <div class="box-body add-page">
                <div class="row">
                    <div class="col-md-12">

                        <div class="col-md-12">
                            <h4>AIML or CSV:</h4>
                            <p>You can use this form to upload a csv file or an aiml file</p>
                        </div>
                        <div class="col-md-6">
                            <h4>CSV Format:</h4>
                            <p>The format of your csv file should be:</p>
                            <code>"Filename","Pattern","Topic","That","Template","Status"<br/>
                                "Unit Test","BYE","","","{!! htmlentities("<template>Bye!</template>") !!}","A"<br/>
                                "Unit Test","HI","","","{!! htmlentities("<template>Hello!</template>") !!}","A"<br/>
                            </code>
                            <br/>
                        </div>
                        <div class="col-md-6">
                            <h4>CSV Upload Rules:</h4>
                            <ul>
                                <li>The format of the file should be a comma separated csv file</li>
                                <li>The first row should will be ignored (as assume this contains column headings</li>
                                <li>Any columns after the ones listed above will be ignored</li>
                                <li>No changes will be made if there are any errors when processing the file</li>
                                <li>If you want an example of the file, then just download a file from the table page</li>
                                <li>Newly created category groups will be created with the status 'Test'</li>
                            </ul>
                            <br/>
                        </div>

                        <div class="col-md-6">
                            <h4>AIML Format:</h4>
                            <p>If you are uploading an AIML file you should make sure it follows the AIML2.0 standard</p>
                            <br/>
                        </div>
                        <div class="col-md-6">
                            <h4>AIML Upload Rules:</h4>
                            <ul>
                                <li>The filename will be used to create a category group e.g. dinner.aiml will create a category group called 'dinner'</li>
                                <li>No changes will be made if there are any errors when processing the file</li>
                                <li>If you want an example of the file, then just download a file from the table page</li>
                                <li>Newly created category groups will be created with the status 'Test'</li>
                            </ul>
                            <br/>
                        </div>


                        <div class="col-md-12">
                            <p>Select your file to upload</p>
                        </div>

                        {!! Form::open(['url' => 'categoriesUpload', 'data-test'=>$htmlTag.'-upload-form', 'class'=>'validate', 'name'=>$htmlTag.'-upload', 'enctype'=>"multipart/form-data"]) !!}

                        <div class="form-group col-sm-6 col-lg-6">
                            {!! Form::label('file', 'File:') !!}
                            <div class="input-group">
                                <label class="input-group-btn">
                                        <span class="btn btn-primary">
                                        Browse… <input name="aiml_file" type="file" style="display: none;" data-test="{!! $htmlTag !!}-file-button" id="{!! $htmlTag !!}-file-button" data-validation='required'>
                                    </span>
                                </label>
                                <input type="text" class="form-control" readonly="" id="{!! $htmlTag !!}-file-input" data-test="{!! $htmlTag !!}-file-input">
                            </div>
                            <span id="slug-info-message" class="help-block form-info">Select a file from your computer to upload</span>
                        </div>

                        <div class="form-group col-sm-12 col-lg-12">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="processingOptions" id="processingOptions_a" value="append" checked="">
                                    Replace existing and append the new categories which have the same pattern, topic and that in this category group
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="processingOptions" id="processingOptions_b" value="delete">
                                    Delete the existing categories in this category group and create new categories
                                </label>
                            </div>
                        </div>

                        <!-- Status Field -->
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 select2-md">
                            {!! Form::label('language_id', 'Language:', ['data-test'=>"language_id_label"]) !!}
                            <div class="form-group">
                                {!! Form::select('language_id', $languageList, 'en', [  'placeholder'=>'Please Select', 'class' => 'form-control select2 select2 generic', 'data-validation'=>'required', 'data-test'=>"$htmlTag-language_id-select", 'id'=>"$htmlTag-language_id-select"]) !!}

                            </div>
                        </div>


                        <!-- Status Field -->
                        <div class="form-group col-lg-3 col-md-3 col-sm-6 select2-md">
                            {!! Form::label('status', 'Status (only required if uploading an AIML file):', ['data-test'=>"status_label"]) !!}
                            <div class="form-group">
                                {!! Form::select('status', ['A'=>'Active','T'=>'Test','H'=>'Hidden'], 'A', [  'placeholder'=>'Please Select', 'class' => 'col-sm-6 form-control select2 generic', 'data-validation'=>'required', 'data-test'=>"$htmlTag-status-select", 'id'=>"$htmlTag-status-select"]) !!}
                            </div>
                        </div>

                        <div class="clearfix"><br/></div>
                        <br/>
                        <!-- Submit Field -->
                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                            <a href="{{ route('categories.index') }}" class="btn btn-default">Cancel</a>
                        </div>


                    {!! Form::close() !!}
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
