@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Clothing Item Details</h5>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back to Inventory
                </a>
            </div>

            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Item Name:</div>
                    <div class="col-md-8">{{ $inventory->name }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Description:</div>
                    <div class="col-md-8">{{ $inventory->description ?? 'N/A' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Clothing Type:</div>
                    <div class="col-md-8">{{ App\Models\Inventory::CLOTHING_TYPES[$inventory->clothing_type] }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Size:</div>
                    <div class="col-md-8">{{ $inventory->size ?? 'N/A' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Gender:</div>
                    <div class="col-md-8">{{ ucfirst($inventory->gender) }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Color:</div>
                    <div class="col-md-8">{{ $inventory->color ?? 'N/A' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">School House:</div>
                    <div class="col-md-8">{{ $inventory->school_house ?? 'N/A' }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Quantity:</div>
                    <div class="col-md-8">
                        <span class="{{ $inventory->quantity < 10 ? 'text-danger fw-bold' : '' }}">
                            {{ $inventory->quantity }}
                        </span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Price:</div>
                    <div class="col-md-8">{{ number_format($inventory->price, 0, ',', '.') }} ៛</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Added On:</div>
                    <div class="col-md-8">{{ $inventory->created_at->format('M j, Y g:i A') }}</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 fw-bold">Last Updated:</div>
                    <div class="col-md-8">{{ $inventory->updated_at->format('M j, Y g:i A') }}</div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-primary me-2">
                        <i class="bi bi-pencil"></i> Edit Item
                    </a>
                    <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">
                            <i class="bi bi-trash"></i> Delete Item
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection