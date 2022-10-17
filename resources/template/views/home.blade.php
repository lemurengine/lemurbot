@extends('layouts.app')

@section('content')
<section class="content-header">
    <h1 class="pull-left">Home</h1>

    <div class="clearfix"></div>
</section>
<div class="content">
    <div class="clearfix"></div>


    <div class="clearfix"></div>
    <div class="box box-primary">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{ __('You are logged in!') }}
        </div>
    </div>
    <div class="text-center">
    </div>
</div>
@endsection
