@if(!empty($errors) && $errors->any())
        <ul class="alert alert-danger" style="list-style-type: none" data-test="error-list">
            @foreach($errors->all() as $index => $error)
                <li data-test="error-list-{!! $index !!}">{!! $error !!}</li>
            @endforeach
        </ul>
@else
    @foreach (session('flash_notification', collect())->toArray() as $message)
        @if ($message['overlay'])
            @include('lemurbot::flash::modal', [
                'modalClass' => 'flash-modal',
                'title'      => $message['title'],
                'body'       => $message['message'],
                'level'       => $message['level']
            ])
        @else
            <div data-test="form-message-{{ $message['level'] }}" class="alert alert-{{ $message['level'] }} {{ $message['important'] ? 'alert-important' : '' }}" role="alert">
                @if ($message['important'])
                    <button type="button"
                            class="close"
                            data-dismiss="alert"
                            aria-hidden="true"
                    >&times;</button>
                @endif

                {!! $message['message'] !!}
            </div>
        @endif

    @endforeach

    {{ session()->forget('flash_notification') }}
@endif
