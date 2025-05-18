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
                                                <td>
                                                    <!-- Individual Product Checkbox -->
                                                    <input type="checkbox" class="select-product" name="selected_items[]" value="{{ $item['id'] }}" data-index="{{ $index }}" onclick="updateSelection()">
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
                                                    <span class="price-column">Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
                                                </td>
                                                <td class="text-center align-middle">
                                                    <input type="number" 
                                                           class="form-control quantity-input" 
                                                           value="{{ $item['quantity'] }}" 
                                                           min="1" 
                                                           data-index="{{ $index }}" 
                                                           data-price="{{ $item['price'] }}" 
                                                           style="width: 80px;">
                                                </td>
                                                <td class="align-middle">
                                                    <span class="price-column" id="item-total-{{ $index }}">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                                </td>
                                                <td>
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

                    <!-- Summary Column -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Order Summary</h5>
                                
                                <!-- Voucher Section -->
                                <div class="mb-4">
                                    <h6 class="mb-2">Apply Voucher</h6>
                                    <form id="voucher-form" method="POST" action="{{ route('cart.applyVoucher') }}">
                                        @csrf
                                        <div class="input-group mb-2">
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="voucher_code" 
                                                   placeholder="Enter voucher code"
                                                   value="{{ session('voucher_code', '') }}">
                                            <button class="btn btn-primary" type="submit">
                                                {{ session('voucher_code') ? 'Update' : 'Apply' }}
                                            </button>
                                        </div>
                                      
                                        @if(session('voucher_error'))
                                            <div class="alert alert-danger p-2 mb-2">
                                                {{ session('voucher_error') }}
                                            </div>
                                        @endif
                                      
                                        @if(session('voucher_success'))
                                            <div class="alert alert-success p-2 mb-2 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="fas fa-check-circle me-2"></i>
                                                    {{ session('voucher_success') }}
                                                </div>
                                                <a href="{{ route('cart.removeVoucher') }}" class="btn btn-sm btn-outline-danger">
                                                    Remove
                                                </a>
                                            </div>
                                        @endif
                                    </form>
                                    <small class="text-muted">Available vouchers: CHILLBRO (min Rp200,000), COOLMAN, GOODDAY</small>
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
                                    @endif
                                    
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping:</span>
                                        <span id="shipping-display">Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tax:</span>
                                        <span id="tax-display">Rp{{ number_format($tax, 0, ',', '.') }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold fs-5">
                                        <span>Total:</span>
                                        <span id="total-display">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                    <form action="{{ route('checkout.form') }}" method="GET">
                                        <button type="submit" class="btn btn-primary" style="margin-top: 7px;" id="checkout-button" disabled>
                                            Proceed to Checkout
                                        </button>
                                    </form>
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
    // Toggle "Select All" checkbox and update all product selections
    function toggleSelectAll(selectAllCheckbox) {
        const checkboxes = document.querySelectorAll('.select-product');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        updateSelection();
    }

    // Update the "Proceed to Checkout" button based on the selected items
    function updateSelection() {
        let selectedItems = 0;
        const selectedCheckboxes = document.querySelectorAll('.select-product:checked');
        selectedItems = selectedCheckboxes.length;

        const checkoutButton = document.getElementById('checkout-button');
        checkoutButton.disabled = selectedItems === 0;
        updateCart();
    }

    // Update cart totals when quantity or selection changes
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', updateCart);
    });

    function formatRupiah(angka) {
        return 'Rp' + angka.toLocaleString('id-ID');
    }

    function updateCart() {
        let subtotal = 0;
        const shipping = 5000;

        document.querySelectorAll('.select-product:checked').forEach((checkbox, index) => {
            const price = parseInt(checkbox.closest('tr').querySelector('.quantity-input').getAttribute('data-price')) || 0;
            const qty = parseInt(checkbox.closest('tr').querySelector('.quantity-input').value) || 0;
            const total = price * qty;

            const itemTotalEl = document.getElementById('item-total-' + index);
            if (itemTotalEl) itemTotalEl.textContent = formatRupiah(total);
            subtotal += total;
        });

        const tax = Math.round(subtotal * 0.1);
        const voucherDiscount = {{ is_numeric(session('voucher_discount')) ? session('voucher_discount') : 0 }};
        const grandTotal = subtotal + tax + shipping - voucherDiscount;

        document.getElementById('subtotal-display').textContent = formatRupiah(subtotal);
        document.getElementById('shipping-display').textContent = formatRupiah(shipping);
        document.getElementById('tax-display').textContent = formatRupiah(tax);
        document.getElementById('total-display').textContent = formatRupiah(grandTotal);
        
        if (voucherDiscount > 0) {
            const voucherDisplay = document.getElementById('voucher-display');
            if (voucherDisplay) {
                voucherDisplay.textContent = '-Rp' + voucherDiscount.toLocaleString('id-ID');
            }
        }
    }

    updateCart();
</script>

@endsection
