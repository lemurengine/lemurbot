@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Upload Word Transformations
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
                            @if(LemurPriv::isAdmin(Auth::user()))
                            Admin Example
                            <code><br/>
"Lang","1stPerson","2ndPerson","3rdPerson","3rdPerson Female","3rdPerson Male","Master Data?"<br/>
"en","place","place","places","","","1"<br/>
"en","matter","matter","matters","","","1"<br/>
                             </code>
                            @endif
                            <br/>
                            Normal User Example
                            <code><br/>
"Lang","1stPerson","2ndPerson","3rdPerson","3rdPerson Female","3rdPerson Male""<br/>
"en","place","place","places","",""<br/>
"en","matter","matter","matters","",""<br/>
                            </code>
                            <br/>
                        </div>
                        <div class="col-md-12">
                            <h4>Prerequisites:</h4>
                            <h4>Upload Rules:</h4>
                            <ul>
                                <li>The format of the file should be a comma separated csv file</li>
                                <li>The first row should will be ignored (as assume this contains column headings</li>
                                <li>Any columns after ones shown in the example will be ignored</li>
                                <li>No changes will be made if there are any errors when processing the file</li>
                                <li>If you want an example of the file, then just download a file from the table page</li>
                                @if(LemurPriv::isAdmin(Auth::user()))
                                <li>Only admins can add a master data value</li>
                                @endif
                            </ul>
                            <br/>
                        </div>

                        <div class="col-md-12">
                            <h4>Select File:</h4>
                            {!! Form::open(['url' => 'wordTransformationsUpload', 'data-test'=>$htmlTag.'-upload-form', 'class'=>'validate', 'name'=>$htmlTag.'-upload', 'enctype'=>"multipart/form-data"]) !!}

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
                                        Append the new records to the word spelling group
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="processingOptions" id="processingOptions_b" value="delete">
                                        Delete all existing records from the word spelling group and create new
                                    </label>
                                </div>
                            </div>

                            <div class="clearfix"><br/></div>
                            <br/>
                            <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Upload', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route('wordTransformations.index') }}" class="btn btn-default">Cancel</a>
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
    {{ Html::script('js/validation.js') }}
    {{ Html::script('js/select2.js') }}



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
