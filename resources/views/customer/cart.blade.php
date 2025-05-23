@extends('layouts.app')

@section('title', 'Shopping Cart - Chile Mart')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold mb-4">Shopping Cart</h1>
            
            @if(count($cartItems) == 0)
                <div class="alert alert-info">
                    Your cart is empty. <a href="{{ route('products') }}">Browse products</a> to add items.
                </div>
            @else
                <div class="row">
                    <!-- Product List Column -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>
                                                    <!-- Select All Checkbox -->
                                                    <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)">
                                                    <label for="select-all" class="ms-2">Select All</label>
                                                </th>
                                                <th>Product</th>
                                                <th class="text-left">Price</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-left">Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
    @foreach($cartItems as $index => $item)
    <tr>
        <td class="align-middle">
            <!-- Individual Product Checkbox -->
            <input type="checkbox" class="select-product" name="selected_items[]" 
                   value="{{ $item['id'] }}" 
                   data-price="{{ $item['price'] }}" 
                   data-quantity="{{ $item['quantity'] }}" 
                   data-index="{{ $index }}" 
                   onclick="updateSelection()">
        </td>
        <td>
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/products-img/' . ($item['image'] ?? 'default.jpg')) }}"
                    alt="{{ $item['name'] ?? 'Product Image' }}"
                    class="img-thumbnail me-3"
                    style="width: 80px; height: 80px; object-fit: cover;">
                <div>
                    <h5 class="mb-1">{{ $item['name'] }}</h5>
                </div>
            </div>
        </td>
                                                <td class="align-middle">
    <span class="price-column" data-price="{{ $item['price'] }}">
        Rp{{ number_format($item['price'], 0, ',', '.') }}
    </span>
</td>
                                                <td class="text-center align-middle">
    <form action="{{ route('cart.update', ['productId' => $item['id']]) }}" method="POST">
        @csrf
        <input type="number" 
               name="quantity"
               class="form-control quantity-input" 
               value="{{ $item['quantity'] }}" 
               min="1" 
               data-index="{{ $index }}" 
               data-price="{{ $item['price'] }}" 
               style="width: 80px;"
               onchange="this.form.submit()">
    </form>
</td>
                                                <td class="align-middle text-center">
                                                    <span class="price-column" id="item-total-{{ $index }}">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                                </td>
                                                <td class="align-middle text-center">
                                                     <form action="{{ route('cart.remove', ['productId' => $item['id'] ?? 0]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="select-all"
                                                            onclick="toggleSelectAll(this)">
                                                        <label for="select-all" class="ms-2">Select All</label>
                                                    </th>
                                                    <th>Product</th>
                                                    <th class="text-left">Price</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-left">Total</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($cartItems as $index => $item)
                                                    <tr>
                                                        <td class="align-middle">
                                                            <input type="checkbox" class="select-product"
                                                                name="selected_items[]" value="{{ $item['id'] }}"
                                                                data-price="{{ $item['price'] }}"
                                                                data-quantity="{{ $item['quantity'] }}"
                                                                data-index="{{ $index }}"
                                                                onclick="updateSelection()">
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('images/products-img/' . ($item['image'] ?? 'default.jpg')) }}"
                                                                    alt="{{ $item['name'] ?? 'Product Image' }}"
                                                                    class="img-thumbnail me-3"
                                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                                                <div>
                                                                    <h5 class="mb-1">{{ $item['name'] }}</h5>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="price-column" data-price="{{ $item['price'] }}">
                                                                Rp{{ number_format($item['price'], 0, ',', '.') }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <form
                                                                action="{{ route('cart.update', ['productId' => $item['id']]) }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="number" name="quantity"
                                                                    class="form-control quantity-input"
                                                                    value="{{ $item['quantity'] }}" min="1"
                                                                    data-index="{{ $index }}"
                                                                    data-price="{{ $item['price'] }}" style="width: 80px;"
                                                                    onchange="this.form.submit()">
                                                            </form>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="price-column"
                                                                id="item-total-{{ $index }}">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <form
                                                                action="{{ route('cart.remove', ['productId' => $item['id'] ?? 0]) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Order Summary -->
                                <div class="border-top pt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span id="subtotal-display">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    @if(session('voucher_discount'))
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Voucher Discount:</span>
                                        <span id="voucher-display">-Rp{{ number_format(session('voucher_discount'), 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.select-product');
            const selectAllCheckbox = document.getElementById('select-all');
            const checkoutButton = document.getElementById('checkout-button');
            const checkoutForm = document.getElementById('checkout-form');
            const voucherForm = document.getElementById('voucher-form');
            const selectedItemsInput = document.getElementById('selected-items');
            const selectedItemsVoucherInput = document.getElementById('selected-items-voucher');

            const selectedFromSession = {!! json_encode(session('selected_items', [])) !!};

            function updateSummary() {
                const selectedCheckboxes = document.querySelectorAll('.select-product:checked');
                let subtotal = 0;

                selectedCheckboxes.forEach(checkbox => {
                    const price = parseFloat(checkbox.getAttribute('data-price'));
                    const quantity = parseInt(checkbox.getAttribute('data-quantity'));
                    subtotal += price * quantity;
                });

                const shippingFee = 5000;
                const tax = Math.round(subtotal * 0.1);
                const voucherDisplay = document.getElementById('voucher-display');
                const voucherDiscount = voucherDisplay ? parseFloat(voucherDisplay.textContent.replace(/[^\d]/g,
                    '')) || 0 : 0;
                const total = subtotal + shippingFee + tax - voucherDiscount;

                document.getElementById('subtotal-display').textContent = 'Rp' + subtotal.toLocaleString('id-ID');
                document.getElementById('tax-display').textContent = 'Rp' + tax.toLocaleString('id-ID');
                document.getElementById('total-display').textContent = 'Rp' + total.toLocaleString('id-ID');
            }

            function updateSelectedItems() {
                const selected = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                selectedItemsInput.value = selected.join(',');
                if (selectedItemsVoucherInput) {
                    selectedItemsVoucherInput.value = selected.join(',');
                }
                console.log("Selected items updated:", selected);
            }

            function updateCheckoutButton() {
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                checkoutButton.disabled = !anyChecked;
            }

            function applySessionSelections() {
                checkboxes.forEach(cb => {
                    if (selectedFromSession.includes(cb.value)) {
                        cb.checked = true;
                    }
                });

                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            }

            function updateAll() {
                updateSelectedItems();
                updateSummary();
                updateCheckoutButton();
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                    updateAll();
                });
            });

            selectAllCheckbox.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateAll();
            });

            if (voucherForm) {
                voucherForm.addEventListener('submit', function() {
                    updateSelectedItems();
                });
            }

            checkoutForm.addEventListener('submit', function(e) {
                updateSelectedItems();
                if (!selectedItemsInput.value) {
                    e.preventDefault();
                    alert('Please select at least one item to proceed to checkout.');
                }
            });

            applySessionSelections();
            updateAll();
        });
    </script>


@endsection