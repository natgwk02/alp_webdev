@extends('layouts.app')

@section('title', 'Shopping Cart - Chile Mart')

@section('content')

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="fw-bold mb-4">Shopping Cart</h1>

                @if (count($cartItems) == 0)
                    <div class="alert alert-info">
                        Your cart is empty. <a href="{{ route('products') }}">Browse products</a> to add items.
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
                                                                name="selected_items[]" value="{{ $item->id }}"
                                                                data-price="{{ $item->product->orders_price }}"
                                                                data-quantity="{{ $item->quantity }}"
                                                                data-index="{{ $index }}"
                                                                onclick="updateSelection()">
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('images/products-img/' . ($item->product->products_image ?? 'default.jpg')) }}"
                                                                    alt="{{ $item->product->name ?? 'Product Image' }}"
                                                                    class="img-thumbnail me-3"
                                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                                                <div>
                                                                    <h5 class="mb-1">{{ $item->product->products_name }}</h5>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="price-column"
                                                                data-price="{{ $item->product->orders_price }}">
                                                                Rp{{ number_format($item->product->orders_price, 0, ',', '.') }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <form
                                                                action="{{ route('cart.update', ['productId' => $item->products_id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="number" name="quantity"
                                                                    class="form-control quantity-input"
                                                                    value="{{ $item->quantity }}" min="1"
                                                                    data-index="{{ $index }}"
                                                                    data-price="{{ $item->product->orders_price }}"
                                                                    style="width: 80px;" onchange="this.form.submit()">
                                                            </form>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <span class="price-column"
                                                                id="item-total-{{ $index }}">Rp{{ number_format($item->product->orders_price * $item->quantity, 0, ',', '.') }}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <form
                                                                action="{{ route('cart.remove', ['productId' => $item->products_id]) }}"
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
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-4">Order Summary</h5>

                                    {{-- Add Voucher & Checkout Forms here if needed --}}
                                    {{-- Example: <form id="checkout-form" ...> --}}

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2"><span>Subtotal:</span><span
                                                id="subtotal-display">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2"><span>Shipping Fee:</span><span
                                                id="shipping-display">Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2"><span>Tax (10%):</span><span
                                                id="tax-display">Rp{{ number_format($tax, 0, ',', '.') }}</span></div>
                                        @if (session('voucher_discount'))
                                            <div class="d-flex justify-content-between mb-2 text-success">
                                                <span>Voucher Discount:</span>
                                                <span
                                                    id="voucher-display">-Rp{{ number_format(session('voucher_discount'), 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold mt-3 border-top pt-3">
                                        <span>Total:</span>
                                        <span id="total-display">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                    </div>

                                    {{-- Example: <button id="checkout-button" ...>Checkout</button></form> --}}
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
            // --- Get Elements ---
            const checkboxes = document.querySelectorAll('.select-product');
            const selectAllCheckbox = document.getElementById('select-all');
            const checkoutButton = document.getElementById('checkout-button'); // Ensure this ID exists in your HTML
            const checkoutForm = document.getElementById('checkout-form'); // Ensure this ID exists
            const voucherForm = document.getElementById('voucher-form'); // Ensure this ID exists
            const selectedItemsInput = document.getElementById('selected-items'); // Ensure this ID exists
            const selectedItemsVoucherInput = document.getElementById(
            'selected-items-voucher'); // Ensure this ID exists
            const subtotalDisplay = document.getElementById('subtotal-display');
            const shippingDisplay = document.getElementById(
            'shipping-display'); // Assuming you want to update this too
            const taxDisplay = document.getElementById('tax-display');
            const totalDisplay = document.getElementById('total-display');
            const voucherDisplay = document.getElementById('voucher-display');

            // --- Functions ---
            function updateSummary() {
                let subtotal = 0;
                document.querySelectorAll('.select-product:checked').forEach(checkbox => {
                    const price = parseFloat(checkbox.getAttribute('data-price'));
                    const quantity = parseInt(checkbox.getAttribute('data-quantity'));
                    subtotal += price * quantity;
                });

                const shippingFee = parseFloat(shippingDisplay ? shippingDisplay.textContent.replace(/[^\d]/g, '') :
                    5000) || 5000;
                const tax = Math.round(subtotal * 0.1);
                const voucherDiscount = voucherDisplay ? parseFloat(voucherDisplay.textContent.replace(/[^\d]/g,
                    '')) || 0 : 0;
                const total = subtotal + shippingFee + tax - voucherDiscount;

                if (subtotalDisplay) subtotalDisplay.textContent = 'Rp' + subtotal.toLocaleString('id-ID');
                if (taxDisplay) taxDisplay.textContent = 'Rp' + tax.toLocaleString('id-ID');
                if (totalDisplay) totalDisplay.textContent = 'Rp' + total.toLocaleString('id-ID');
            }

            function updateSelectedItems() {
                const selected = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (selectedItemsInput) selectedItemsInput.value = selected.join(',');
                if (selectedItemsVoucherInput) selectedItemsVoucherInput.value = selected.join(',');
                // console.log("Selected items updated:", selected); // Uncomment for debugging
            }

            function updateCheckoutButton() {
                if (!checkoutButton) return;
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                checkoutButton.disabled = !anyChecked;
            }

            function updateAll() {
                updateSelectedItems();
                updateSummary();
                updateCheckoutButton();
            }

            // --- Event Listeners ---
            checkboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    selectAllCheckbox.checked = checkboxes.length > 0 && Array.from(checkboxes)
                        .every(cb => cb.checked);
                    updateAll();
                });
            });

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateAll();
                });
            }

            if (voucherForm) {
                voucherForm.addEventListener('submit', function() {
                    updateSelectedItems();
                });
            }

            if (checkoutForm) {
                checkoutForm.addEventListener('submit', function(e) {
                    updateSelectedItems();
                    if (selectedItemsInput && !selectedItemsInput.value) {
                        e.preventDefault();
                        alert('Please select at least one item to proceed to checkout.');
                    }
                });
            }

            // --- Initial Call ---
            updateAll();

        });
    </script>

@endsection
