@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">School Clothing Inventory</h5>
                    <div>
                        <a href="{{ route('inventory.export') }}" class="btn btn-success btn-sm me-2">
                            <i class="bi bi-download"></i> Export Excel
                        </a>
                        <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle"></i> Add New Item
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('inventory.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="clothing_type" class="form-control">
                                    <option value="">All Types</option>
                                    @foreach(App\Models\Inventory::CLOTHING_TYPES as $key => $value)
                                        <option value="{{ $key }}" {{ request('clothing_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="size" class="form-control">
                                    <option value="">All Sizes</option>
                                    @foreach(App\Models\Inventory::SIZES as $size)
                                        <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="gender" class="form-control">
                                    <option value="">All Genders</option>
                                    @foreach(App\Models\Inventory::GENDERS as $key => $value)
                                        <option value="{{ $key }}" {{ request('gender') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    @if($inventories->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Gender</th>
                                        <th>Color</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventories as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ App\Models\Inventory::CLOTHING_TYPES[$item->clothing_type] }}</td>
                                        <td>{{ $item->size ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($item->gender) }}</td>
                                        <td>{{ $item->color ?? 'N/A' }}</td>
                                        <td>
                                            <span class="{{ $item->quantity < 10 ? 'text-danger fw-bold' : '' }}">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }} ៛</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('inventory.show', $item->id) }}" class="btn btn-info btn-sm" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this item?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i> No clothing items found in inventory. 
                            <a href="{{ route('inventory.create') }}" class="alert-link">Add your first item</a>.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection