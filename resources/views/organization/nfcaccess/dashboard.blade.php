@extends('organization.layout')

@section('content')
@section('title')
   NFC Access
@endsection

<div class="card mb-5 mb-xl-8">
    <div class="card-body py-3">
        <div class="table-responsive">
            <select class="form-control" name="" id="">
                <option value="">Select organization</option>
            </select><br>
            <select class="form-control" name="" id="">
                <option value="">Select group</option>
            </select><br>
            <select class="form-control" name="" id="">
                <option value="">Select receiver</option>
            </select><br>
            <input class="form-control" placeholder="Chip ID" type="text"><br>

            <button class="btn btn-sm btn-primary" type="submit">Confirm</button>
        </div>
    </div>
</div>

@endsection
