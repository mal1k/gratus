@extends('organization.layout')
@section('title', 'Tippers')
@section('content')
<form method="POST" action="{{ route('organizationtippers.store', $org->slug) }}">
    @csrf
    <div class="form-tipper">
        <p style="color:red">
          @foreach ($errors->all() as $error)
            {{ $error }}
          @endforeach
    </p>
        <label for="tipperEmail">Email</label>
        <input name="email" type="email" class="form-control" id="tipperEmail" placeholder="Email" value="{{ $tipper->email ?? '' }}">
    </div>
    <button type="submit" class="btn btn-primary mt-3" >Invite</button>
</form>
@endsection
