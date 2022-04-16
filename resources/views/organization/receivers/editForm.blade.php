@extends('organization.layout')
@section('title', 'Receiver edit info')
@section('content')
<a href="{{ route('organizationreceivers.index', $org->slug) }}"><button class="btn btn-primary mb-3">Go back</button></a>

<form method="POST" action="{{ route('organizationreceiversinfo.update', [$org->slug, $receiver->id]) }}" class="p-5">
@csrf
    <div class="form-receiver">
        <label for="receiverName">Name</label>
        <input name="first_name" type="text" class="form-control" id="receiverName" placeholder="Name" value="{{ $receiver->first_name ?? '' }}">
    </div>
    <div class="form-receiver">
        <label for="receiverSurname">Surname</label>
        <input name="last_name" type="text" class="form-control" id="receiverSurname" placeholder="Surname" value="{{ $receiver->last_name ?? '' }}">
    </div>
    <div class="form-receiver">
        <label for="receiverEmail">Email</label>
        <input name="email" type="email" class="form-control" id="receiverEmail" placeholder="Email" value="{{ $receiver->email ?? '' }}">
    </div>

    <div class="form-receiver">
        <label for="kycChoice">KYC</label><br>

        <input type="radio" @if ( $receiver->kyc_status == 'Active' ) checked @endif id="kycChoice1"
         name="kyc_status" value="200">
        <label for="kycChoice1">Active</label><br>

        <input type="radio" @if ( $receiver->kyc_status == 'Rejected' ) checked @endif id="kycChoice2"
         name="kyc_status" value="10">
        <label for="kycChoice2">Rejected</label><br>

        <input type="radio" @if ( $receiver->kyc_status == 'Processing' ) checked @endif id="kycChoice3"
         name="kyc_status" value="102">
        <label for="kycChoice3">Processing</label><br>
    </div>

    <button type="submit" class="btn btn-sm btn-primary mt-2">Confirm</button>
</form>
@endsection
