@extends('layouts.app')

@section('title', 'Shopping Cart - Chile Mart')

@section('content')
<style>
    /* Make table scrollable on small screens */
    .table-responsive {
        overflow-x: auto;
    }

    /* Ensure checkout button width is 100% on smaller screens */
    .btn-primary {
        width: 100%;
    }

    /* Add responsive padding for small devices */
    @media (max-width: 767px) {
        .table-responsive {
            overflow-x: scroll;
        }

        .card-body {
            padding: 15px;
        }

        .btn-outline-danger {
            font-size: 12px;
        }

        .table td, .table th {
            font-size: 12px;
        }

        .fw-bold {
            font-size: 16px;
        }

        .card-title {
            font-size: 18px;
        }

        .input-group input,
        .input-group button {
            font-size: 12px;
        }
    }

    /* For medium and larger screens */
    @media (min-width: 768px) {
        .card-body {
            padding: 25px;
        }

        .fw-bold {
            font-size: 18px;
        }

        .card-title {
            font-size: 20px;
        }
    }
</style>
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
                        <div class="col-12 col-md-8">
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>
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
                                                @foreach ($cartItems as $index => $item)
                                                    <tr>
                                                        <td class="align-middle">
                                                            <input type="checkbox" class="select-product"
                                                                name="selected_items[]" value="{{ $item->products_id }}"
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
                                                            <span class="price-column" data-price="{{ $item->product->orders_price }}">
                                                                Rp{{ number_format($item->product->orders_price, 0, ',', '.') }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            <form action="{{ route('cart.update', ['productId' => $item->products_id]) }}" method="POST">
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
                                                            <span class="price-column" id="item-total-{{ $index }}">Rp{{ number_format($item->product->orders_price * $item->quantity, 0, ',', '.') }}</span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <form action="{{ route('cart.remove', ['productId' => $item->products_id]) }}" method="POST">
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
                        <div class="col-12 col-md-4 mt-4 mt-md-0">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-4">Order Summary</h5>

                                    @if (session('voucher_code'))
                                        <div class="alert alert-info py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    Voucher <strong>{{ strtoupper(session('voucher_code')) }}</strong> is active.
                                                    @if (session('voucher_discount'))
                                                        <small class="d-block">Discount: Rp{{ number_format(session('voucher_discount'), 0, ',', '.') }}</small>
                                                    @endif
                                                </div>
                                                <form action="{{ route('cart.removeVoucher') }}" method="GET" class="ms-2" style="margin-bottom: 0;">
                                                    <button type="submit" class="btn btn-sm btn-danger py-1 px-2">Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <form id="voucher-form" action="{{ route('cart.applyVoucher') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="voucher_code" class="form-label small">Enter Voucher Code:</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="voucher_code" name="voucher_code" placeholder="Voucher code"
                                                        required>
                                                    <input type="hidden" name="selected_items_voucher" id="selected-items-voucher">
                                                    <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                                                </div>
                                            </div>
                                            @if (session('voucher_error'))
                                                <div class="alert alert-danger alert-dismissible fade show py-2 small">
                                                    {{ session('voucher_error') }}
                                                    <button type="button" class="btn-close btn-sm py-1 px-2" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            @endif
                                        </form>
                                    @endif

                                    @if (session('voucher_success'))
                                        <div class="alert alert-success alert-dismissible fade show mt-2 py-3 small">
                                            {{ session('voucher_success') }}
                                            <button type="button" class="btn-close btn-sm mt-1 mx-2 py-3 px-2" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span id="subtotal-display">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                        </div>

                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tax (10%):</span>
                                            <span id="tax-display">Rp{{ number_format($tax, 0, ',', '.') }}</span>
                                        </div>

                                        @if (session('voucher_discount') && session('voucher_discount') > 0)
                                            <div class="d-flex justify-content-between mb-2 text-success">
                                                <span>Voucher Discount:</span>
                                                <span id="voucher-display">-Rp{{ number_format(session('voucher_discount'), 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex justify-content-between fw-bold mt-3 border-top pt-3">
                                        <span>Total:</span>
                                        <span id="total-display">Rp{{ number_format($total, 0, ',', '.') }}</span>
                                    </div>

                                    <form id="checkout-form" action="{{ route('checkout.form') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="selected_items" id="selected-items">
                                        <button type="submit" class="btn btn-primary w-100 mt-3" id="checkout-button">Proceed to Checkout</button>
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
            const selectedItemsVoucherInput = document.getElementById('selected-items-voucher'); // For voucher

            console.log('DOM Loaded.');
            console.log('Checkboxes found:', checkboxes.length);
            console.log('selectedItemsInput element:', selectedItemsInput);
            console.log('selectedItemsVoucherInput element:',
                selectedItemsVoucherInput);
            console.log('checkoutForm element:', checkoutForm);
            console.log('checkoutButton element:', checkoutButton);

            const localStorageKey = 'cartSelection_{{ Auth::check() ? Auth::id() : 'guest' }}';

            function saveSelectionToLocalStorage() {
                const selectedIds = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                localStorage.setItem(localStorageKey, JSON.stringify(selectedIds));
                console.log('Selection saved to localStorage:', selectedIds);
            }

            function loadSelectionFromLocalStorage() {
                const savedSelection = localStorage.getItem(localStorageKey);
                console.log('Loading from localStorage:', savedSelection);
                if (savedSelection) {
                    try {
                        return JSON.parse(savedSelection);
                    } catch (e) {
                        console.error("Error parsing saved selection from localStorage:", e);
                        localStorage.removeItem(localStorageKey);
                        return [];
                    }
                }
                return [];
            }

            const selectedItemsFromServer = {!! json_encode($selectedItemsOnLoad ?? []) !!};
            console.log('Selected items from server (on load):', selectedItemsFromServer);

            function applySelectionsToCheckboxes(idsToSelect) {
                console.log('Applying selections to checkboxes:', idsToSelect);
                if (checkboxes.length === 0) {
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                    return;
                }
                checkboxes.forEach(cb => {
                    cb.checked = idsToSelect.includes(cb.value);
                });
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb
                        .checked);
                }
            }

            function updateSummary() {
                console.log('updateSummary called.');
                let subtotal = 0;
                document.querySelectorAll('.select-product:checked').forEach(checkbox => {
                    const price = parseFloat(checkbox.getAttribute('data-price'));
                    const quantity = parseInt(checkbox.getAttribute('data-quantity'));
                    if (!isNaN(price) && !isNaN(quantity)) {
                        subtotal += price * quantity;
                    }
                });

                const taxDisplay = document.getElementById('tax-display');
                const totalDisplay = document.getElementById('total-display');
                const voucherDisplaySpan = document.getElementById('voucher-display');
                const subtotalDisplay = document.getElementById('subtotal-display');

                const tax = Math.round(subtotal * 0.1);
                let voucherDiscount = 0;
                if (voucherDisplaySpan && voucherDisplaySpan.textContent) {
                    const parsedVoucher = parseFloat(voucherDisplaySpan.textContent.replace(/[^\d]/g, ''));
                    if (!isNaN(parsedVoucher)) {
                        voucherDiscount = parsedVoucher;
                    }
                }

                const total = subtotal + tax - voucherDiscount;

                if (subtotalDisplay) subtotalDisplay.textContent = 'Rp' + subtotal.toLocaleString('id-ID');
                if (taxDisplay) taxDisplay.textContent = 'Rp' + tax.toLocaleString('id-ID');
                if (totalDisplay) totalDisplay.textContent = 'Rp' + total.toLocaleString('id-ID');
                console.log('Summary updated: Subtotal=', subtotal, 'Total=', total);
            }

            function updateSelectedItems() {
                console.log('updateSelectedItems called.');
                const selected = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                console.log('IDs selected by checkboxes for hidden input:', selected);

                // Always update checkout input
                if (selectedItemsInput) {
                    selectedItemsInput.value = selected.join(',');
                    console.log('Populated selectedItemsInput (checkout) with:', selectedItemsInput.value);
                } else {
                    console.error('selectedItemsInput (checkout) is null in updateSelectedItems.');
                }

                // Only update voucher input if it exists (it won't exist when voucher is already applied)
                if (selectedItemsVoucherInput) {
                    selectedItemsVoucherInput.value = selected.join(',');
                    console.log('Populated selectedItemsVoucherInput with:', selectedItemsVoucherInput.value);
                } else {
                    console.log(
                        'selectedItemsVoucherInput not found (probably because voucher is already applied or form is hidden).'
                    );
                }
            }

            function updateCheckoutButton() {
                if (!checkoutButton) {
                    console.warn('checkoutButton element not found in updateCheckoutButton.');
                    return;
                }
                const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                checkoutButton.disabled = !anyChecked;
                console.log('updateCheckoutButton: anyChecked =', anyChecked, ', button disabled =', checkoutButton
                    .disabled);
            }

            function updateAll() {
                console.log('updateAll called.');
                updateSelectedItems();
                updateSummary();
                updateCheckoutButton();
            }

            let initialSelectionIdsToApply = [];
            if (selectedItemsFromServer && selectedItemsFromServer.length > 0) {
                console.log('Using selected items from server for initial selection.');
                initialSelectionIdsToApply = selectedItemsFromServer;
            } else {
                console.log('No server-side selection, trying localStorage.');
                initialSelectionIdsToApply = loadSelectionFromLocalStorage();
                if (initialSelectionIdsToApply.length === 0 && checkboxes.length > 0) {
                    console.log('No localStorage selection, defaulting to NO items selected.');
                    initialSelectionIdsToApply = [];
                }
            }
            applySelectionsToCheckboxes(initialSelectionIdsToApply);
            saveSelectionToLocalStorage();
            updateAll();

            checkboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    console.log('Checkbox changed:', cb.value, 'Checked:', cb.checked);
                    if (selectAllCheckbox) {
                        selectAllCheckbox.checked = checkboxes.length > 0 && Array.from(checkboxes)
                            .every(c => c.checked);
                    }
                    saveSelectionToLocalStorage();
                    updateAll();
                });
            });

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    console.log('Select All checkbox changed:', this.checked);
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    saveSelectionToLocalStorage();
                    updateAll();
                });
            }

            if (voucherForm) {
                voucherForm.addEventListener('submit', function() {
                    console.log('Voucher form submitting. Updating selected items for voucher.');
                    updateSelectedItems();
                });
            }

            if (checkoutForm) {
                console.log('Checkout form event listener being attached.');
                checkoutForm.addEventListener('submit', function(e) {
                    console.log('Checkout form submit event TRIGGERED.');

                    // Get selected items
                    const selectedIds = Array.from(checkboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);

                    console.log('Selected IDs for checkout:', selectedIds);

                    if (selectedItemsInput) {
                        selectedItemsInput.value = selectedIds.join(',');
                        console.log('Updated selectedItemsInput value:', selectedItemsInput.value);
                    }

                    if (selectedIds.length === 0) {
                        console.log('PREVENTING SUBMISSION: No items selected.');
                        e.preventDefault();
                        alert('Please select at least one item to proceed to checkout.');
                        return false;
                    }

                    if (!selectedItemsInput) {
                        console.error(
                            'CRITICAL ERROR: selectedItemsInput element NOT FOUND. Preventing submission.'
                        );
                        e.preventDefault();
                        alert('An error occurred with the checkout form. Please refresh and try again.');
                        return false;
                    }

                    console.log('ALLOWING SUBMISSION with selected items:', selectedItemsInput.value);
                });
            } else {
                console.error('CRITICAL ERROR: Checkout form (checkout-form) NOT FOUND in DOM.');
            }
        });
    </script>
@endsection
