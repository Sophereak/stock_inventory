@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daily Sales Report</h2>
    <p>Total Sales Today: <strong>{{ number_format($totalSales, 2) }}</strong></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity Sold</th>
                <th>Unit Price</th>
                <th>Total Amount</th>
                <th>Sold At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->inventory->name }}</td>
                <td>{{ $sale->quantity_sold }}</td>
                <td>{{ number_format($sale->unit_price, 2) }}</td>
                <td>{{ number_format($sale->total_amount, 2) }}</td>
                <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
