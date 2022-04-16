@extends('organization.layout')
@section('title', 'Receiver info')
@section('content')
<a href="{{ route('organizationreceivers.index', $org->slug) }}"><button class="btn btn-primary mb-3">Go back</button></a>

    <div class="form-receiver">
        <label for="receiverEmail">Email</label>
        <input disabled name="email" disabled type="email" class="form-control" id="receiverEmail" value="{{ $receiver->email ?? '' }}">
    </div>
    <div class="form-receiver">
        <label for="receiverName">Name</label>
        <input disabled name="first_name" type="text" class="form-control" id="receiverName" value="{{ $receiver->first_name ?? '' }}">
    </div>
    <div class="form-receiver">
        <label for="receiverSurname">Surname</label>
        <input disabled name="last_name" type="text" class="form-control" id="receiverSurname" value="{{ $receiver->last_name ?? '' }}">
    </div>
    <div class="form-receiver">
        KYC
        <input disabled type="text" class="form-control" id="orgName" value="{{ $receiver->kyc_status ?? '' }}">
    </div>
    <div class="form-receiver">
        <label for="orgName">Organization of receiver</label>
        <input disabled type="text" class="form-control" id="orgName" value="<?php $receiver_org = \App\Models\Organization::where('id', '=', "$receiver->org_id")->first(); echo $receiver_org->name; ?>">
    </div>
    <div class="form-receiver">
        <label for="orgName">Group of receiver</label>
        <input disabled type="text" class="form-control" id="orgName" value="<?php if ( isset($receiver->if_in_group) ) { $receiver_group = \App\Models\organizationgroups::where('id', '=', "$receiver->if_in_group")->first(); echo $receiver_group->name; } ?>">
    </div>
    <a href="{{ route('organizationtransactions.index', ['name' => $org->slug, 'model' => 'Receiver', 'id' => $receiver->id]) }}" class="btn btn-sm btn-primary mt-2">
        Transactions
    </a>
@endsection
