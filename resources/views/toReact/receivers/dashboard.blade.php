<h1>Receivers</h1>

@isset ($receivers)
    @foreach (@receivers as $receiver)
        {{ $receiver->title }} - {{ $receiver->content }}
    @endforeach
@endisset
