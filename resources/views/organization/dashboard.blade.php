@extends('organization.layout')
@section('title', 'Dashboard')

@section('content')
<!-- Title of organization: {{ $org->name }}<br>
Email of organization: {{  $org->email  }}<br>
Slug: {{ $org->slug  }} -->

<div class="row">
    <div class="content pb-5 col-6">
        <h1>Receivers:</h1>
        <p>
            <b>Total:</b> <?php echo \App\Models\Receiver::where('org_id', '=', "$org->id")->count(); ?><br>
            <b>Per last month:</b>
            <?php
                $receivers_last_month = \App\Models\Receiver::where('created_at','>=',Carbon\Carbon::now()->subdays(30))->count();
            ?>
            {{ $receivers_last_month }}
        </p>
    </div>
    <div class="content pb-5 col-6">
        <h1>Tippers: (static)</h1>
            <b>Total:</b> 0<br>
            <b>Per last month:</b> 0
        </h2>
    </div>
</div>

<div class="content">
    <h1>Total revenue: 275 <b>(static)</b></h1>
    <font color="blue">blue</font> is previous month.<br>
    <font color="green">green</font> is current month.
</div>
<div id="revenue_chart" style="width: 50%; height: 300px;"></div>

<script>
    const revenue_chart = new Chartisan({
      el: '#revenue_chart',
      url: "@chart('revenue_chart')",
    });
</script>

@endsection
