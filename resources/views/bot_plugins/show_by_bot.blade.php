<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">
    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>
        <h4>Word Transformation Plugins</h4>
        @include('lemurbot::bot_plugins.word_transformation_plugins')

        <div class="clearfix"></div>
        <h4>Custom Plugins</h4>
        @include('lemurbot::bot_plugins.custom_plugins')
    </div>
</section>
@push('scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click','.open-info-button',function(){


                let title = $(this).attr('data-title');
                let description = $(this).attr('data-description');

                //coin field - which should be disabled in this form
                $('div#showInfoModal p#info-body').html(description);
                $('div#showInfoModal #info-title').html(title);

                $('div#showInfoModal').modal('show');
            });


        });
    </script>
@endpush

<div class="modal" id="showInfoModal" tabindex="-1" role="dialog" data-test='show-info-modal'>
    <div class="modal-dialog modal-lg info" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="info-title"></h4>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                <p id="info-body"></p>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <!-- Submit Field -->
                <div class="form-group col-sm-12">
                    <button type="button" class="btn btn-secondary" data-test="show-info-modal-close" data-dismiss="modal">Close</button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

