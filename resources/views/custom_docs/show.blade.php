@extends('lemurbot::layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{ $customDoc->title }}
        </h1>
    </section>
    <div class="content">


        <div class="box box-primary">
            <!-- Box Comment -->
            <div class="box box-widget">
                <div class="box-header with-border">
                    <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{!! Avatar::create($customDoc->user->name)->toBase64() !!}" alt="user image">
                        <span class="username">{{ $customDoc->user->name }}</span>
                        <span class="description">{{ $customDoc->updated_at }}</span>
                    </div>
                    <!-- /.user-block -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- post text -->
                    {!!  Illuminate\Mail\Markdown::parse($customDoc->body) !!}
                </div>
                <!-- /.box-footer -->
                <div class="box-footer">
                    <a href="{{ route('customDocs.index') }}" class="btn btn-default">Back</a>
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection
