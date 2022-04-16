@extends('organization.layout')
@section('title', 'Groups')
@section('content')
<div class="card">
<div class="card-body">
@csrf
@isset($group)
    @method('PUT')
@endisset
    <a href="{{ route('organizationgroups.index', $org->slug) }}"><button class="btn btn-primary mb-3">Go back</button></a>

    <div class="form-receiver">
        <label for="receiverID">ID</label>
        <input disabled name="last_name" type="text" class="form-control" id="receiverID" value="{{ $group->id ?? '' }}">
    </div>

    <div class="form-group">
        <label for="nameOfGroup">Name</label>
        <input disabled name="name" type="text" class="form-control" id="nameOfGroup" placeholder="name" value="{{ $group->name ?? '' }}">
    </div>

    <div class="form-receiver">
        <label for="receiverID">Date created</label>
        <input disabled name="date_created" type="text" class="form-control" id="receiverID" value="{{ date('d/m/Y', strtotime($group->created_at)) ?? '' }}">
    </div>


    <div class="form-group">
        <label for="users">Receivers:</label>
        @php( $ifreceivers = 0 )
        @foreach ($receivers as $receiver)
            <li>{{ $receiver->first_name . ' ' . $receiver->last_name }}</li>
            @php( $ifreceivers += 1 )
        @endforeach
    </div>

    @if ( $ifreceivers > 0 )
        <a href="{{ route('organization.schedule.form', ['name' => $org->slug, 'id' => $group->id]) }}" class="btn btn-primary mb-3"><button>Shifts</button></a>
    @endif

    <div class="form-group">
        <label for="users">Tippers:</label>
        @php( $iftippers = 0 )
        @foreach ($tippers as $tipper)
            <li>{{ $tipper->first_name . ' ' . $tipper->last_name }}</li>
            @php( $iftippers += 1 )
        @endforeach
    </div>

    @if ( $iftippers > 0 )
        <a href="#" class="btn btn-primary mb-3"><button>Transactions</button></a>
    @endif

</div>
</div>
@endsection
