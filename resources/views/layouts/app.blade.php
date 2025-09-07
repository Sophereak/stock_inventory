<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Inventory System') }}</title>

    <!-- Fonts & Icons -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        .navbar-nav .nav-item {
            margin-right: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
        }

        .nav-link i {
            margin-right: 5px;
        }

        .inventory-count {
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
            margin-left: 5px;

        }

        .navbar-brand-modern {
            margin-left: 10px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            background-clip: text;
            /* Standard */
            -webkit-background-clip: text;
            /* Chrome/Safari */
            -webkit-text-fill-color: transparent;
            color: transparent;
            /* fallback */
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="bi bi-box-seam fs-3" style="color:#667eea;"></i>
                    <span class="navbar-brand-modern fs-4 fw-bold">ChoronaiStore</span>
                </a>

                <!-- Hamburger -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('inventory.index') }}">
                                <i class="bi bi-boxes"></i> Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sales.index') }}">
                                <i class="bi bi-currency-exchange"></i> Sales
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sales.report.daily') }}">
                                <i class="bi bi-graph-up"></i> Daily Report
                            </a>
                        </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person"></i> Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-gear"></i> Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>


        <main class="py-4">
            @yield('content')
        </main>

        @auth
        <!-- Quick Sale Modal -->
        <div class="modal fade" id="quickSaleModal" tabindex="-1">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quick Sale</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <select id="quick-sale-item" class="form-select mb-2">
                            <option value="">Select Item</option>
                            @foreach(\App\Models\Inventory::where('quantity', '>', 0)->get() as $item)
                            <option value="{{ $item->id }}" data-stock="{{ $item->quantity }}">
                                {{ $item->name }} ({{ $item->quantity }} left)
                            </option>
                            @endforeach
                        </select>
                        <input type="number" id="quick-sale-qty" class="form-control" value="1" min="1" placeholder="Qty">
                        <div id="quick-sale-message" class="mt-2 small text-muted"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="process-quick-sale">Sell</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const quickSaleModal = new bootstrap.Modal(document.getElementById('quickSaleModal'));
                const quickSaleItem = document.getElementById('quick-sale-item');
                const quickSaleQty = document.getElementById('quick-sale-qty');
                const quickSaleMessage = document.getElementById('quick-sale-message');
                const processQuickSale = document.getElementById('process-quick-sale');

                document.addEventListener('keydown', e => {
                    if (e.ctrlKey && e.key === 's') {
                        e.preventDefault();
                        quickSaleModal.show();
                    }
                });

                function updateQuickSaleMessage() {
                    const selectedOption = quickSaleItem.options[quickSaleItem.selectedIndex];
                    const stock = selectedOption?.getAttribute('data-stock') || 0;
                    const quantity = parseInt(quickSaleQty.value) || 0;

                    if (selectedOption && stock && quantity) {
                        if (quantity > stock) {
                            quickSaleMessage.className = 'mt-2 small text-danger';
                            quickSaleMessage.textContent = `Only ${stock} items available!`;
                            processQuickSale.disabled = true;
                        } else {
                            quickSaleMessage.className = 'mt-2 small text-success';
                            quickSaleMessage.textContent = `Will sell ${quantity} items`;
                            processQuickSale.disabled = false;
                        }
                    } else {
                        quickSaleMessage.textContent = '';
                        processQuickSale.disabled = !selectedOption || !quantity;
                    }
                }

                quickSaleItem.addEventListener('change', updateQuickSaleMessage);
                quickSaleQty.addEventListener('input', updateQuickSaleMessage);

                processQuickSale.addEventListener('click', async function() {
                    const itemId = quickSaleItem.value;
                    const quantity = parseInt(quickSaleQty.value);
                    if (!itemId || !quantity) return;

                    try {
                        const response = await fetch('{{ route("sales.quick") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                inventory_id: itemId,
                                quantity_sold: quantity
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            alert('Sale recorded! Remaining stock: ' + result.remaining_stock);
                            quickSaleModal.hide();
                            quickSaleItem.value = '';
                            quickSaleQty.value = '1';
                            quickSaleMessage.textContent = '';
                            window.location.reload();
                        } else {
                            alert('Error: ' + result.message);
                        }
                    } catch (error) {
                        alert('Error: ' + error.message);
                    }
                });

                document.getElementById('quickSaleModal').addEventListener('hidden.bs.modal', function() {
                    quickSaleItem.value = '';
                    quickSaleQty.value = '1';
                    quickSaleMessage.textContent = '';
                });
            });
        </script>
        @endauth
    </div>
</body>

</html>