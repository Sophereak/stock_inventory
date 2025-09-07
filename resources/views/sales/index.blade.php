@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sales Records</h5>
                    <div>
                        <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Record Sale
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Sales Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h6>Total Sales Value</h6>
                                    <h4>{{ number_format($totalSales, 0, ',', '.') }} ៛</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6>Today's Sales</h6>
                                    <h4>{{ number_format($todaySales, 0, ',', '.') }} ៛</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6>Total Records</h6>
                                    <h4>{{ $sales->total() }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($sales->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Item</th>
                                        <th>Qty Sold</th>
                                        <th>Unit Price</th>
                                        <th>Total Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sales as $sale)
                                    <tr>
                                        <td>{{ $sale->created_at->format('M d, Y H:i') }}</td>
                                        <td>{{ $sale->inventory->name }}</td>
                                        <td>{{ $sale->quantity_sold }}</td>
                                        <td>{{ number_format($sale->unit_price, 0, ',', '.') }} ៛</td>
                                        <td>{{ number_format($sale->total_amount, 0, ',', '.') }} ៛</td>
                                        <td>
                                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this sale record? Stock will be restored.')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $sales->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i> No sales records found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection