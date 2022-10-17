@extends('lemurbot::layouts.app')
@push('scripts')
    {{ Html::style('https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css') }}
@endpush
@section('content')
    <section class="content-header">
        <h1>
            Custom Docs
        </h1>
   </section>
   <div class="content">
       @include('lemurbot::layouts.feedback')
       <div class="box box-primary">
           <div class="box-body edit-page">
               <div class="row">
                   <div class="col-md-12">
                       {!! Form::model($customDoc, ['route' => ['customDocs.update', $customDoc->slug], 'method' => 'patch', 'data-test'=>$htmlTag.'-edit-form', 'class'=>'validate', 'name'=>$htmlTag.'-edit']) !!}

                            @include('lemurbot::custom_docs.fields')

                            <!-- Submit Field -->
                            <div class="form-group col-sm-12">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route('customDocs.index') }}" class="btn btn-default">Cancel</a>
                            </div>


                       {!! Form::close() !!}
                    </div>
               </div>
           </div>
       </div>
   </div>
@endsection
<!-- Slug Field Edit Modal -->
@include('lemurbot::layouts.edit_slug_modal')
    @push('scripts')
        {{ Html::script('js/validation.js') }}
        {{ Html::script('https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js') }}
        <script>
            const editor = new EasyMDE({
                autofocus: true,
                blockStyles: {
                    bold: "__",
                    italic: "_",
                },
                element: $('#body_field')[0],
                hideIcons: ["fullscreen"],
                indentWithTabs: false,
                insertTexts: {
                    horizontalRule: ["", "\n\n-----\n\n"],
                    image: ["![](http://", ")"],
                    link: ["[", "](https://)"],
                    table: ["", "\n\n| Column 1 | Column 2 | Column 3 |\n| -------- | -------- | -------- |\n| Text     | Text      | Text     |\n\n"],
                },
                lineWrapping: false,
                parsingConfig: {
                    allowAtxHeaderWithoutSpace: true,
                    strikethrough: false,
                    underscoresBreakWords: true,
                },
                placeholder: "Type here...",

                promptURLs: true,
                promptTexts: {
                    image: "Custom prompt for URL:",
                    link: "Custom prompt for URL:",
                },

                shortcuts: {
                    drawTable: "Cmd-Alt-T"
                },
                showIcons: ["code", "table"],
                spellChecker: false,
                status: false,
                styleSelectedText: false,
                sideBySideFullscreen: false,
                syncSideBySidePreviewScroll: false,
                tabSize: 4,
            });

        </script>
    @endpush

