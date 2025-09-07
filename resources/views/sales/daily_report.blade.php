@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daily Sales Report - {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h2>

    <form method="GET" action="{{ route('sales.report.daily') }}" class="mb-3">
        <input type="date" name="date" value="{{ $date }}" class="form-control w-auto d-inline-block">
        <button type="submit" class="btn btn-primary">View</button>
        <button type="button" class="btn btn-success" id="send-telegram">Send to Telegram</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Size</th>
                <th>Quantity Sold</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th>Sold At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sale->inventory->name ?? 'N/A' }}</td>
                <td>{{ $sale->inventory->size ?? 'N/A' }}</td>
                <td>{{ $sale->quantity_sold }}</td>
                <td>{{ number_format($sale->unit_price, 0, ',', '.') }}</td>
                <td>{{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                <td>{{ $sale->sold_at }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Total</th>
                <th>{{ $totalQuantity }}</th>
                <th></th>
                <th>{{ number_format($totalSales, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

<script>
document.getElementById('send-telegram').addEventListener('click', async function() {
    const date = "{{ $date }}";
    const response = await fetch("{{ route('sales.report.daily.send') }}?date=" + date, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    });

    const result = await response.json();
    if(result.success) {
        alert('Daily report sent to Telegram!');
    } else {
        alert('Failed to send report: ' + result.message);
    }
});
</script>
@endsection
