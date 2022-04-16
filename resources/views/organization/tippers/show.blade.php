@extends('organization.layout')
@section('title', 'Tipper info')
@section('content')
<a href="{{ route('organizationtippers.index', $org->slug) }}"><button class="btn btn-primary mb-3">Go back</button></a>

    <div class="form-tipper">
        <label for="tipperEmail">Email</label>
        <input disabled name="email" disabled type="email" class="form-control" id="tipperEmail" value="{{ $tipper->email ?? '' }}">
    </div>
    <div class="form-tipper">
        <label for="tipperName">Name</label>
        <input disabled name="first_name" type="text" class="form-control" id="tipperName" value="{{ $tipper->first_name ?? '' }}">
    </div>
    <div class="form-tipper">
        <label for="tipperSurname">Surname</label>
        <input disabled name="last_name" type="text" class="form-control" id="tipperSurname" value="{{ $tipper->last_name ?? '' }}">
    </div>
    <div class="form-tipper">
        <label for="payment">Payment</label>
        <input disabled type="text" class="form-control" id="payment" value="Payment here">
    </div>
    <a href="{{ route('organizationtransactions.index', ['name' => $org->slug, 'model' => 'Tipper', 'id' => $tipper->id]) }}" class="btn btn-sm btn-primary mt-2">
        Transactions
    </a>
@endsection
