@extends('organization.layout')
@section('title', 'Receivers')
@section('content')
<form method="POST" action="{{ route('organizationreceivers.store', $org->slug) }}">
    @csrf
    <div class="form-receiver">
        <p style="color:red">
          @foreach ($errors->all() as $error)
            {{ $error }}
          @endforeach
    </p>
        <label for="receiverEmail">Email</label>
        <input name="email" type="email" class="form-control" id="receiverEmail" placeholder="Email" value="{{ $receiver->email ?? '' }}">
    </div>
    <button type="submit" class="btn btn-primary mt-3" >Invite</button>
</form>
@endsection
