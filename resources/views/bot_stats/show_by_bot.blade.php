@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.css"/>
@endpush
<div class="clearfix"></div>
<section id="show-by-bot-{!! $htmlTag !!}-details" class="main-form">



    <!-- Forked Id Field -->
    <div class="content">
        <div class="clearfix"></div>


        <div class="col-md-2 col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Bot Rating</h3>
                </div>
                <div class="box-body">
                    @include('lemurbot::bot_ratings.show_by_bot')
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="col-md-2 col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Today's Stats</h3>
                </div>
                <div class="box-body">
                    <p>Conversation Count: {!! $todayConversationStat !!}</p>
                    <p>Turns Count: {!! $todayTurnStat !!}</p>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="col-md-2 col-sm-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">All Time Stats</h3>
                </div>
                <div class="box-body">
                    <p>Conversation Count: {!! $allTimeConversationStat !!}</p>
                    <p>Turns Count: {!! $allTimeTurnStat !!}</p>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Download</h3>
                </div>
                <div class="box-body">
                    <form method="POST" action="/bot/stats/{{$bot->slug}}/download" name="download_stats">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label for="date_from">Date From:</label>
                                <input type="date" class="form-control" name="date_from" />
                            </div>
                            <div class="col-md-4">
                                <label for="date_to">Date To:</label>
                                <input type="date" class="form-control" name="date_to" />
                            </div>
                            <div class="col-md-4">
                                <label for="save">Action:</label>
                                <input type="submit" class="btn btn-success" name="action" value="Download CSV"/>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">This Months Conversations</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChartMonthConversations" style="height: 230px; width: 802px;" height="460" width="1604"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">This Months Turns</h3>
                </div>
                <div class="box-body">
                        <canvas id="barChartMonthTurns" style="height: 230px; width: 802px;" height="460" width="1604"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Last 12 Months Conversations</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChartYearConversations" style="height: 230px; width: 802px;" height="460" width="1604"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Last 12 Months Turns</h3>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChartYearTurns" style="height: 230px; width: 802px;" height="460" width="1604"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->

            </div>





    </div>
</section>



@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>



    <script>
        //the turns per day in a month chart
        var ctx = document.getElementById('barChartYearConversations').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [@foreach ($monthsInYearKey as $key)"{{ $key }}",@endforeach],
                datasets: [
                    {

                        label               : 'Conversations',
                        fillColor           : 'rgba(210, 214, 222, 1)',
                        strokeColor         : 'rgba(210, 214, 222, 1)',
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : [@foreach ($yearlyConversationStat as $stat)"{{ $stat['data'] }}",@endforeach]
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }

            }
        });
    </script>

    <script>
        //the turns per day in a month chart
        var ctx = document.getElementById('barChartYearTurns').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [@foreach ($monthsInYearKey as $key)"{{ $key }}",@endforeach],
                datasets: [
                    {
                        label               : 'Turns',
                        fillColor           : 'rgba(210, 214, 222, 1)',
                        strokeColor         : 'rgba(210, 214, 222, 1)',
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : [@foreach ($yearlyTurnStat as $stat)"{{ $stat['data'] }}",@endforeach]
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        //the turns per day in a month chart
        var ctx = document.getElementById('barChartMonthConversations').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [@foreach ($daysInMonthKey as $key)"{{ $key }}",@endforeach],
                datasets: [
                    {
                        label               : 'Conversations',
                        fillColor           : 'rgba(210, 214, 222, 1)',
                        strokeColor         : 'rgba(210, 214, 222, 1)',
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : [@foreach ($monthlyConversationStat as $stat)"{{ $stat }}",@endforeach]
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <script>
        //the turns per day in a month chart
        var ctx = document.getElementById('barChartMonthTurns').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [@foreach ($daysInMonthKey as $key)"{{ $key }}",@endforeach],
                datasets: [
                    {
                        label               : 'Turns',
                        fillColor           : 'rgba(210, 214, 222, 1)',
                        strokeColor         : 'rgba(210, 214, 222, 1)',
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : [@foreach ($monthlyTurnStat as $stat)"{{ $stat }}",@endforeach]
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

@endpush

