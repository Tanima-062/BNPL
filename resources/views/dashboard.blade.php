<!DOCTYPE html>
<html>
<head>
    <title>BNPL Dashboard</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .card { border: 1px solid #ddd; padding: 12px; margin-bottom: 10px; }
        .paid { color: green; }
        .pending { color: orange; }
        .overdue { color: red; }
    </style>
</head>
<body>

<h1>BNPL Dashboard</h1>

@foreach($purchases as $purchase)

<div class="card">

    <h3>Purchase #{{ $purchase->id }}</h3>

    <p>Total: {{ $purchase->total_amount }} cents</p>
    <p>Paid: {{ $purchase->paid_amount }}</p>
    <p>Outstanding: {{ $purchase->outstanding_amount }}</p>

    <h4>Installments</h4>

    <ul>
        @foreach($purchase->installments as $i)
            <li class="{{ $i->status }}">
                #{{ $i->sequence }}
                - {{ $i->amount }}
                - {{ $i->status }}
                - due {{ $i->due_date }}
            </li>
        @endforeach
    </ul>

</div>

@endforeach

</body>
</html>