@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Welcome to School Clothing Inventory System!</h4>
                    <p class="mt-3">You are logged in as an administrator.</p>
                    
                    <!-- Dashboard Stats -->
                    <div class="row mt-4">
                        <div class="col-md-3 mb-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Items</h5>
                                    <h3>{{ $totalItems }}</h3>
                                    <p class="card-text">in inventory</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Inventory Value</h5>
                                    <h3>{{ number_format($totalInventoryValue, 0, ',', '.') }} ៛</h3>
                                    <p class="card-text">total worth</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Low Stock</h5>
                                    <h3>{{ $lowStockItems }}</h3>
                                    <p class="card-text">items need restock</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Sales</h5>
                                    <h3>{{ number_format($totalSales, 0, ',', '.') }} ៛</h3>
                                    <p class="card-text">revenue generated</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Quick Actions</div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('inventory.index') }}" class="btn btn-primary mb-2">
                                            <i class="bi bi-boxes"></i> View Inventory
                                        </a>
                                        <a href="{{ route('inventory.create') }}" class="btn btn-success mb-2">
                                            <i class="bi bi-plus-circle"></i> Add New Item
                                        </a>
                                        <a href="{{ route('sales.index') }}" class="btn btn-info mb-2">
                                            <i class="bi bi-currency-exchange"></i> View Sales
                                        </a>
                                        <a href="{{ route('sales.create') }}" class="btn btn-warning mb-2">
                                            <i class="bi bi-cart-plus"></i> Record Sale
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">Recently Added Items</div>
                                <div class="card-body">
                                    @if($recentItems->count() > 0)
                                        <div class="list-group">
                                            @foreach($recentItems as $item)
                                                <a href="{{ route('inventory.edit', $item->id) }}" class="list-group-item list-group-item-action">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">{{ $item->name }}</h6>
                                                        <small>{{ $item->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <p class="mb-1">Size: {{ $item->size }}, Qty: {{ $item->quantity }}</p>
                                                    <small>Price: {{ number_format($item->price, 0, ',', '.') }} ៛</small>
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted">No items added yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Today's Sales -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">Today's Sales Summary</div>
                                <div class="card-body">
                                    <h4 class="text-center">{{ number_format($todaySales, 0, ',', '.') }} ៛</h4>
                                    <p class="text-center text-muted">Total sales today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection