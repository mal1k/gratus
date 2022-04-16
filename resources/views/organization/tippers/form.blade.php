@extends('organization.invite-layout')
@section('title', 'Tippers')
@section('content')
<a href="{{ route('organizationtippers.index', $org->slug) }}"><button class="btn btn-primary mb-3">Go back</button></a>

<form method="POST" action="{{ route('organizationtippers.invite', [$org->slug, $code]) }}" class="p-5">
@csrf
    <div class="form-tipper">
        <label for="tipperName">Name</label>
        <input name="first_name" type="text" class="form-control" id="tipperName" placeholder="Name" value="">
    </div>
    <div class="form-tipper">
        <label for="tipperSurname">Surname</label>
        <input name="last_name" type="text" class="form-control" id="tipperSurname" placeholder="Surname" value="">
    </div>
    <div class="form-tipper">
        <label for="tipperEmail">Email</label>
        <input name="email" disabled type="email" class="form-control" id="tipperEmail" placeholder="Email" value="{{ $tipper->email ?? '' }}">
    </div>
    <div class="form-tipper">
        <label for="exampleFormControlInput1">Password of tipper</label>
        <input name="password" type="password" class="form-control" id="exampleFormControlInput1" placeholder="Password" value="">
    </div>

    <button type="submit">Confirm</button>
</form>
@endsection
