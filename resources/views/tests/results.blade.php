@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">Tests</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('lemurbot::layouts.feedback')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body col-md-12">
                @if($error)

                    <div class="alert alert-danger">An error has occurred whilst running the tests.</div>

                @elseif($testCount==0)

                    <div class="alert alert-danger">No tests have run.</div>
                @else

                    <div class="alert alert-info">{!! $testCount !!} tests have run.<br/>
                        {!! $successCount !!} tests passed<br/>
                        {!! $failCount !!} tests failed<br/>
                        {!! $unknownCount !!} tests require manual inspection
                    </div>



                @endif



                @if(!empty($results))



                        <div class="col-xs-12">
                            <div class="box">

                                <!-- /.box-header -->
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <tbody><tr>
                                            <th>#</th>
                                            <th>ID</th>
                                            <th>Input</th>
                                            <th>Output</th>
                                            <th>Result</th>
                                            <th>Error</th>
                                        </tr>

                                        @foreach($results as $index => $result)
                                        <tr>
                                            <td>{{ $index }}</td>
                                            <td>{{ $result['id'] }}</td>
                                            <td>{{ $result['input']}}</td>
                                            <td>{{ $result['output'] }}</td>
                                            <td><span class="label label-{!! $result['result']['label'] !!}">{!! $result['result']['outcome'] !!}</span></td>
                                            <td>@if(!empty($result['error']))
                                                    {{ !empty($result['error']['message'])?$result['error']['message']:''}} | {{!empty($result['error']['line'])?$result['error']['line']:''}} | {{!empty($result['error']['file'])?$result['error']['file']:''}}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>

                @endif
            </div>
            <div class="col-md-12">
                <a class="btn btn-success" href="{!! url('/test/run') !!}">Start Tests</a>
            </div>
            <div class="clearfix"></div>
            <br/>

        </div>


    </div>
@endsection
