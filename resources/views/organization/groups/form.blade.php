@extends('organization.layout')
@section('title', 'Groups')
@section('content')
<div class="card">
<div class="card-body">
<a href="{{ route('organizationgroups.index', $org->slug) }}"><button class="btn btn-primary mb-3">Go back</button></a>
<form method="POST"
@if ( isset($group) )
    action="{{ route('organizationgroups.update', [$org->slug, $group->id]) }}"
@else
    action="{{ route('organizationgroups.store', $org->slug) }}"
@endif
>
@csrf
@isset($group)
    @method('PUT')
@endisset

    <div class="form-group">
        <label for="exampleFormControlInput1">Name of group</label>
        <input name="name" type="text" class="form-control" id="exampleFormControlInput1" placeholder="name" value="{{ $group->name ?? '' }}">
    </div>
    <div class="form-group">
        <label for="users"><b>Receivers:</b></label>

        @isset($group->users)
        @foreach($group->users as $groups)
            <?php
            if ( !empty($groups['receivers']) )
                foreach($groups['receivers'] as $newReceiver) { $receiver_array[] = $newReceiver; }
            {{-- if ( !empty($groups['tippers']) )
                foreach($groups['tippers'] as $newTipper) { $tipper_array[] = $newTipper; } --}}
            ?>
        @endforeach
        @endisset
        @isset($users)
          <select name="users[group][receivers][]" multiple="multiple" class="form-select col receiver-basic-multiple">
            @foreach ( $users as $user )
                <option
                    @if ( isset($group->users) && !empty($receiver_array) && in_array($user->id, $receiver_array) )
                        selected
                    @endif
                    value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</option>
            @endforeach
          </select>
        @endisset

        {{-- <label for="tippers"><b>Tippers:</b></label>
        @isset($tippers)
          <select name="users[group][tippers][]" multiple="multiple" class="form-select col tipper-basic-multiple">
            @foreach ( $tippers as $tipper )
                <option
                    @if ( isset($group->users) && !empty($tipper_array) && in_array($tipper->id, $tipper_array) )
                        selected
                    @endif
                    value="{{ $tipper->id }}">{{ $tipper->first_name . ' ' . $tipper->last_name }}</option>
            @endforeach
          </select>
        @endisset --}}
    </div>
    <button class="btn btn-primary mt-3" type="submit">@if (isset($group)) Update @else Create @endif</button>
</form>
</div>
</div>

<script>
jQuery(document).ready(function() {
    jQuery('.receiver-basic-multiple').select2();
    jQuery('.tipper-basic-multiple').select2();
});

</script>
@endsection
