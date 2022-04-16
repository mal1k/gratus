@extends('organization.invite-layout')
@section('title', 'Receivers')
@section('content')
<form method="POST" action="{{ route('organizationreceivers.invite', [$org->slug, $code]) }}" class="p-5">
@csrf
    <p style="color: red;">
        @if($errors->any())
            {{ implode('', $errors->all(':message')) }}
        @endif
    </p>

    <div class="form-receiver">
        <label for="receiverName">Name</label>
        <input name="first_name" type="text" class="form-control" id="receiverName" placeholder="Name" value="{{ old('first_name') }}">
    </div>
    <div class="form-receiver">
        <label for="receiverSurname">Surname</label>
        <input name="last_name" type="text" class="form-control" id="receiverSurname" placeholder="Surname" value="{{ old('last_name') }}">
    </div>
    <div class="form-receiver">
        <label for="receiverEmail">Email</label>
        <input name="email" disabled type="email" class="form-control" id="receiverEmail" placeholder="Email" value="{{ $receiver->email ?? '' }}">
    </div>
    <div class="form-receiver">
        <label for="exampleFormControlInput1">Password of receiver</label>
        <input name="password" type="password" class="form-control" id="exampleFormControlInput1" placeholder="Password" value="">
    </div>
    <div class="form-receiver">
        <label for="repeat_password">Repeat Password</label>
        <input name="repeat_password" type="password" class="form-control" id="repeat_password" placeholder="Repeat Password" value="">
    </div>

    <button type="submit">Confirm</button>
</form>
@endsection
