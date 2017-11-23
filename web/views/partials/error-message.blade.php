@if ($_session->has('_error'))
    <div class="alert alert-danger" role="alert">
        @if (is_array($_session->get('_error')))
            <ul>
                @foreach($_session->get('_error') as $error)
                    <li> {!! $error !!}</li>
                @endforeach
            </ul>
        @else
            <p>{!! $_session->get('_error') !!}</p>
        @endif
    </div>
@endif
