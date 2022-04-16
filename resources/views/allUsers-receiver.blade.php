<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
<script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>

<title>Organization info</title>

<div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
        <span class="fs-4">Gratus. All users</span>
      </a>
    </header>
</div>
<h1>Edit receiver:</h1>

<form method="POST" action="{{ route('receiverSave') }}">
@csrf
    <input type="hidden" name="id" value="{{ $receiver->id }}">
    <div class="form-group">
        <label for="exampleFormControlInput1">First name</label>
        <input name="first_name" type="text" class="form-control" id="exampleFormControlInput1" placeholder="First name" value="{{ $receiver->first_name ?? '' }}">
    </div>
    <div class="form-group">
        <label for="exampleFormControlInput1">Last name</label>
        <input name="last_name" type="text" class="form-control" id="exampleFormControlInput1" placeholder="Last name" value="{{ $receiver->last_name ?? '' }}">
    </div>
    <div class="form-group">
        <label for="org_id">Organization:</label>
        <select name="org_id" multiple="multiple" class="form-select col">
            @isset($organizations)
            @foreach ( $organizations as $organization )
                <option
                    @if ( $organization->id == $receiver->org_id )
                        selected
                    @endif
                    value="{{ $organization->id }}">{{ $organization->name }}</option>
            @endforeach
            @endisset
        </select>
    </div>
    <button type="submit">Update</button>
</form>
