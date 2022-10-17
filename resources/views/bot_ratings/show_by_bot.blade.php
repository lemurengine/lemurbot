@if($botRatingCount==0)

    <p class="profile-stars">
        <i class="fa fa-star-o"></i>
        <i class="fa fa-star-o"></i>
        <i class="fa fa-star-o"></i>
        <i class="fa fa-star-o"></i>
        <i class="fa fa-star-o"></i>
    </p>
    <p>This bot is currently unrated.</p>
@else




    <p class="profile-stars">
            @if($botRatingAvg==0)
                <i class="fa fa-star-o"></i>
            @elseif($botRatingAvg<=0.5)
                <i class="fa fa-star-half-o"></i>
            @elseif($botRatingAvg>1.5)
                <i class="fa fa-star"></i>
            @endif

            @if($botRatingAvg>1 && $botRatingAvg<=1.5)
                <i class="fa fa-star-half-o"></i>
            @elseif($botRatingAvg>1.5)
                <i class="fa fa-star"></i>
            @else
                <i class="fa fa-star-o"></i>
            @endif


            @if($botRatingAvg>2 && $botRatingAvg<=2.5)
                <i class="fa fa-star-half-o"></i>
            @elseif($botRatingAvg>2.5)
                <i class="fa fa-star"></i>
            @else
                <i class="fa fa-star-o"></i>
            @endif


            @if($botRatingAvg>3 && $botRatingAvg<=3.5)
                <i class="fa fa-star-half-o"></i>
            @elseif($botRatingAvg>3.5)
                <i class="fa fa-star"></i>
            @else
                <i class="fa fa-star-o"></i>
            @endif


            @if($botRatingAvg>4&& $botRatingAvg<=4.5)
                <i class="fa fa-star-half-o"></i>
            @elseif($botRatingAvg>4.5)
                <i class="fa fa-star"></i>
            @else
                <i class="fa fa-star-o"></i>
            @endif
                <span class="pull-right">Rated {!! $botRatingAvg !!} out of 5. ({!! $botRatingCount !!} votes).</span>

    </p>

    <a class='btn btn-sm btn-danger delete-button openDeleteDataTableModal' data-test="delete-button">
        <i class="glyphicon glyphicon-trash"> </i>Reset All Ratings
    </a>

@endif
@push('scripts')
    <script>
        $(document).ready(function() {

            $(document).on('click','.openDeleteDataTableModal',function(){
                $('div#deleteDataTableModal').modal('show');
            });

        });

    </script>
    @endpush

<div class="modal" id="deleteDataTableModal" tabindex="-1" role="dialog" data-test='{!! $htmlTag !!}-delete-modal'>
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action='/botRatings/reset' method='POST' data-test='{!! $htmlTag !!}-delete-form' class='validate' name='{!! $htmlTag !!}-delete'>
                <input name="_method" type="hidden" value="DELETE">
                <input name="bot_id" type="hidden" value="{!! $bot->slug !!}">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Delete All Ratings</h4>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">You can only delete ALL the rating for this bot? Are you sure you want to delete them all?</div>
                    {{ Form::hidden('redirect_url', url()->current(),['data-test'=>"{$htmlTag}-redirect-url"]) }}
                    <div class="clearfix"></div>
                </div>
                <div class="modal-footer">
                    <!-- Submit Field -->
                    <div class="form-group col-sm-12">
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger', 'data-test'=>"{$htmlTag}-delete-form-submit"]) !!}
                        <button type="button" class="btn btn-secondary"  data-test="{!! $htmlTag !!}-delete-modal-close" data-dismiss="modal">Cancel</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
</div>
