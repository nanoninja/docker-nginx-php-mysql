@if ($_session->has('_message'))
    <div class="alert alert-success" role="alert">
        @if (is_array($_session->get('_message')))
            <ul>
                @foreach($_session->get('_message') as $message)
                    <li> {!! $message !!}</li>
                @endforeach
            </ul>
        @else
            <p>{!! $_session->get('_message') !!}</p>
        @endif
    </div>
@endif
