<h1>
@isset($MotivationWord) Update {{ $MotivationWord->title }} @endisset
@empty($MotivationWord) Create motivation @endempty
</h1>

<form method="POST"
    @if ( isset($MotivationWord) )
        action="{{ route('motivation-words.update', $MotivationWord) }}"
    @else
        action="{{ route('motivation-words.store') }}"
    @endif
>
    @csrf
    @isset($MotivationWord)
        @method('PUT')
    @endisset

Motivation:<br>
<input type="text" name="title" value="@isset($MotivationWord){{ $MotivationWord->title }}@endisset"><br>

<button type="submit">Save</button>
</form>
