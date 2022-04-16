<h1>
@isset($push) Update {{ $push->title }} @endisset
@empty($push) Create push @endempty
</h1>

<form method="POST"
    @if ( isset($push) )
        action="{{ route('pushs.update', $push) }}"
    @else
        action="{{ route('pushs.store') }}"
    @endif
>
    @csrf
    @isset($push)
        @method('PUT')
    @endisset

Title:<br>
<input type="text" name="title" value="@isset($push){{ $push->title }}@endisset"><br>

Content:<br>
<textarea name="content" cols="30" rows="10">@isset($push){{ $push->content }}@endisset</textarea>
<br>
<button type="submit">Save</button>
</form>
