@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">{{ isset($inventory) ? 'Edit' : 'Add' }} Clothing Item</div>

            <div class="card-body">
                <form method="POST" action="{{ isset($inventory) ? route('inventory.update', $inventory->id) : route('inventory.store') }}">
                    @csrf
                    @if(isset($inventory))
                        @method('PUT')
                    @endif

                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end">Item Name *</label>
                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $inventory->name ?? '') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
                        <div class="col-md-6">
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $inventory->description ?? '') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="clothing_type" class="col-md-4 col-form-label text-md-end">Clothing Type *</label>
                        <div class="col-md-6">
                            <select id="clothing_type" class="form-control @error('clothing_type') is-invalid @enderror" name="clothing_type" required>
                                <option value="">Select Type</option>
                                @foreach(App\Models\Inventory::CLOTHING_TYPES as $key => $value)
                                    <option value="{{ $key }}" {{ old('clothing_type', $inventory->clothing_type ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('clothing_type')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="size" class="col-md-4 col-form-label text-md-end">Size</label>
                        <div class="col-md-6">
                            <select id="size" class="form-control @error('size') is-invalid @enderror" name="size">
                                <option value="">Select Size</option>
                                @foreach(App\Models\Inventory::SIZES as $size)
                                    <option value="{{ $size }}" {{ old('size', $inventory->size ?? '') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                            @error('size')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="gender" class="col-md-4 col-form-label text-md-end">Gender *</label>
                        <div class="col-md-6">
                            <select id="gender" class="form-control @error('gender') is-invalid @enderror" name="gender" required>
                                @foreach(App\Models\Inventory::GENDERS as $key => $value)
                                    <option value="{{ $key }}" {{ old('gender', $inventory->gender ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="color" class="col-md-4 col-form-label text-md-end">Color</label>
                        <div class="col-md-6">
                            <input id="color" type="text" class="form-control @error('color') is-invalid @enderror" name="color" value="{{ old('color', $inventory->color ?? '') }}">
                            @error('color')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="school_house" class="col-md-4 col-form-label text-md-end">School House</label>
                        <div class="col-md-6">
                            <input id="school_house" type="text" class="form-control @error('school_house') is-invalid @enderror" name="school_house" value="{{ old('school_house', $inventory->school_house ?? '') }}">
                            @error('school_house')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="quantity" class="col-md-4 col-form-label text-md-end">Quantity *</label>
                        <div class="col-md-6">
                            <input id="quantity" type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" value="{{ old('quantity', $inventory->quantity ?? '') }}" required min="0">
                            @error('quantity')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="price" class="col-md-4 col-form-label text-md-end">Price ($) *</label>
                        <div class="col-md-6">
                            <input id="price" type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $inventory->price ?? '') }}" required min="0">
                            @error('price')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ isset($inventory) ? 'Update' : 'Add' }} Item
                            </button>
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection