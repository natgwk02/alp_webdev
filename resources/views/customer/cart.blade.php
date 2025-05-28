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
                                                                    <h5 class="mb-1">{{ $item->product->products_name }}
                                                                    </h5>
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

                                    @if (session('voucher_code'))
                                        <div class="alert alert-info py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    Voucher <strong>{{ strtoupper(session('voucher_code')) }}</strong> is
                                                    active.
                                                    @if (session('voucher_discount'))
                                                        <small class="d-block">Discount:
                                                            Rp{{ number_format(session('voucher_discount'), 0, ',', '.') }}</small>
                                                    @endif
                                                </div>
                                                <form action="{{ route('cart.removeVoucher') }}" method="GET"
                                                    class="ms-2" style="margin-bottom: 0;">
                                                    {{-- Consider changing to POST with @csrf for remove actions if preferred --}}
                                                    <button type="submit"
                                                        class="btn btn-sm btn-danger py-1 px-2">Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Only show form if no voucher is active --}}
                                        <form id="voucher-form" action="{{ route('cart.applyVoucher') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="voucher_code" class="form-label small">Enter Voucher
                                                    Code:</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="voucher_code" name="voucher_code" placeholder="Voucher code"
                                                        required>
                                                    <input type="hidden" name="selected_items_voucher"
                                                        id="selected-items-voucher">
                                                    <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                                                </div>
                                            </div>
                                            @if (session('voucher_error'))
                                                <div class="alert alert-danger alert-dismissible fade show py-2 small">
                                                    {{ session('voucher_error') }}
                                                    <button type="button" class="btn-close btn-sm py-1 px-2"
                                                        data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            @endif
                                        </form>
                                    @endif

                                    @if (session('voucher_success'))
                                        <div class="alert alert-success alert-dismissible fade show mt-2 py-3 small">
                                            {{ session('voucher_success') }}
                                            <button type="button" class="btn-close btn-sm mt-1 mx-2 py-3 px-2"
                                                data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif


                                    {{-- Summary Details (Subtotal, Shipping, Tax, etc.) --}}
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span
                                                id="subtotal-display">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Shipping Fee:</span>
                                            <span
                                                id="shipping-display">Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tax (10%):</span>
                                            <span id="tax-display">Rp{{ number_format($tax, 0, ',', '.') }}</span>
                                        </div>

                                        {{-- This part correctly displays the applied voucher discount --}}
                                        @if (session('voucher_discount') && session('voucher_discount') > 0)
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

                                    <form id="checkout-form" action="{{ route('checkout') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="selected_items" id="selected-items">
                                        <button type="submit" class="btn btn-primary w-100 mt-3"
                                            id="checkout-button">Proceed to Checkout</button>
                                    </form>


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
            const selectedItemsInput = document.getElementById('selected-items'); // For checkout
            const selectedItemsVoucherInput = document.getElementById('selected-items-voucher'); // For voucher form

            // Create a unique key for localStorage, per user if logged in
            const localStorageKey = 'cartSelection_{{ Auth::check() ? Auth::id() : 'guest' }}';

            // --- localStorage Functions ---
            function saveSelectionToLocalStorage() {
                const selectedIds = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value); // These are cart item IDs (strings)
                localStorage.setItem(localStorageKey, JSON.stringify(selectedIds));
            }

            function loadSelectionFromLocalStorage() {
                const savedSelection = localStorage.getItem(localStorageKey);
                if (savedSelection) {
                    try {
                        return JSON.parse(savedSelection);
                    } catch (e) {
                        console.error("Error parsing saved selection from localStorage:", e);
                        localStorage.removeItem(localStorageKey); // Clear corrupted data
                        return [];
                    }
                }
                return []; // Default to empty array if nothing saved or error
            }

            // This data comes from PHP, flashed after specific server actions (e.g., voucher apply)
            const selectedItemsFromServer = {!! json_encode($selectedItemsOnLoad ?? []) !!};

            // Function to apply selections to checkboxes
            function applySelectionsToCheckboxes(idsToSelect) {
                if (checkboxes.length === 0) {
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                    return;
                }
                checkboxes.forEach(cb => {
                    cb.checked = idsToSelect.includes(cb
                        .value); // cb.value is string, idsToSelect should be array of strings
                });
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb
                        .checked);
                }
            }

            // --- Your existing updateSummary, updateSelectedItems, updateCheckoutButton ---
            // Ensure updateSelectedItems uses the current checkbox states to populate hidden fields.
            function updateSummary() {
                let subtotal = 0;
                document.querySelectorAll('.select-product:checked').forEach(checkbox => {
                    const price = parseFloat(checkbox.getAttribute('data-price'));
                    const quantity = parseInt(checkbox.getAttribute('data-quantity'));
                    if (!isNaN(price) && !isNaN(quantity)) {
                        subtotal += price * quantity;
                    }
                });
                // ... (rest of your updateSummary logic for shipping, tax, voucher, total)
                // Make sure it correctly references the display elements
                const shippingDisplay = document.getElementById('shipping-display');
                const taxDisplay = document.getElementById('tax-display');
                const totalDisplay = document.getElementById('total-display');
                const voucherDisplaySpan = document.getElementById(
                    'voucher-display'); // The span for the discount amount
                const subtotalDisplay = document.getElementById('subtotal-display');


                const shippingFeeText = shippingDisplay ? shippingDisplay.textContent : 'Rp5.000';
                const shippingFee = parseFloat(shippingFeeText.replace(/[^\d]/g, '')) || 5000;
                const tax = Math.round(subtotal * 0.1);
                const voucherDiscountText = voucherDisplaySpan ? voucherDisplaySpan.textContent : 'Rp0';
                const voucherDiscount = parseFloat(voucherDiscountText.replace(/[^\d]/g, '')) || 0;
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
            }

            function updateCheckoutButton() {
                if (!checkoutButton) return;
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                checkoutButton.disabled = !anyChecked;
            }
            // --- End of existing update functions ---

            function updateAll() {
                updateSelectedItems();
                updateSummary();
                updateCheckoutButton();
            }


            // --- Initial Page Load Logic ---
            let initialSelectionIdsToApply = [];
            if (selectedItemsFromServer.length > 0) {
                // If server flashed data (e.g., after voucher attempt), use that.
                initialSelectionIdsToApply = selectedItemsFromServer;
            } else {
                // Otherwise, try to load from localStorage (for generic refreshes).
                initialSelectionIdsToApply = loadSelectionFromLocalStorage();
                if (initialSelectionIdsToApply.length === 0 && checkboxes.length > 0) {
                    // If NOTHING from server AND NOTHING from localStorage, and cart has items,
                    // you can choose a default: either all selected or none selected.
                    // Let's default to NONE selected for a truly fresh state.
                    // initialSelectionIdsToApply = Array.from(checkboxes).map(cb => cb.value); // For all selected
                    initialSelectionIdsToApply = []; // For none selected
                }
            }

            applySelectionsToCheckboxes(initialSelectionIdsToApply);
            saveSelectionToLocalStorage(); // Save this initial state to localStorage
            updateAll(); // Update summary etc. based on initial selections


            // --- Event Listeners ---
            checkboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = checkboxes.length > 0 && Array.from(checkboxes)
                            .every(c => c.checked);
                    }
                    saveSelectionToLocalStorage(); // Save selection whenever a checkbox changes
                    updateAll();
                });
            });

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    saveSelectionToLocalStorage(); // Save selection when "Select All" changes
                    updateAll();
                });
            }

            if (voucherForm) {
                voucherForm.addEventListener('submit', function() {
                    // updateSelectedItems() is called to ensure hidden field is populated
                    // with current selection before form submission
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
        });
    </script>

@endsection
