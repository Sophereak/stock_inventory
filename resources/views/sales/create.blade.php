@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Record New Sale</div>

            <div class="card-body">
                <form method="POST" action="{{ route('sales.store') }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="inventory_id" class="col-md-4 col-form-label text-md-end">Item *</label>
                        <div class="col-md-6">
                            <select id="inventory_id" class="form-control @error('inventory_id') is-invalid @enderror" name="inventory_id" required>
                                <option value="">Select Item</option>
                                @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}" data-price="{{ $item->price }}" data-stock="{{ $item->quantity }}">
                                        {{ $item->name }} (Stock: {{ $item->quantity }})
                                    </option>
                                @endforeach
                            </select>
                            @error('inventory_id')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="quantity_sold" class="col-md-4 col-form-label text-md-end">Quantity *</label>
                        <div class="col-md-6">
                            <input id="quantity_sold" type="number" class="form-control @error('quantity_sold') is-invalid @enderror" name="quantity_sold" value="{{ old('quantity_sold') }}" required min="1">
                            @error('quantity_sold')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <small class="form-text text-muted" id="stock-info">Available stock: </small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end">Unit Price</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="unit_price" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-4 col-form-label text-md-end">Total Amount</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="total_amount" readonly>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Record Sale
                            </button>
                            <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inventorySelect = document.getElementById('inventory_id');
    const quantityInput = document.getElementById('quantity_sold');
    const unitPriceInput = document.getElementById('unit_price');
    const totalAmountInput = document.getElementById('total_amount');
    const stockInfo = document.getElementById('stock-info');

    function updatePriceAndStock() {
        const selectedOption = inventorySelect.options[inventorySelect.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        const stock = selectedOption.getAttribute('data-stock');
        
        if (price && stock) {
            unitPriceInput.value = Number(price).toLocaleString('en-US') + ' ៛';
            stockInfo.textContent = 'Available stock: ' + stock;
            calculateTotal();
        } else {
            unitPriceInput.value = '';
            stockInfo.textContent = 'Available stock: ';
            totalAmountInput.value = '';
        }
    }

    function calculateTotal() {
        const price = inventorySelect.options[inventorySelect.selectedIndex]?.getAttribute('data-price') || 0;
        const quantity = quantityInput.value || 0;
        const total = price * quantity;
        totalAmountInput.value = total ? Number(total).toLocaleString('en-US') + ' ៛' : '';
    }

    inventorySelect.addEventListener('change', updatePriceAndStock);
    quantityInput.addEventListener('input', calculateTotal);

    // Initialize on page load
    updatePriceAndStock();
});
</script>
@endsection