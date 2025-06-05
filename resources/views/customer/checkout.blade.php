@extends('layouts.app')
@section('title', 'Checkout - Chile Mart')

@section('content')

    <style>
        .order-summary-sticky .card {
            position: -webkit-sticky;
            position: sticky;
            top: 5.6vw;
            align-self: flex-start;
        }

        @media (max-width: 767.98px) {
            .order-summary-sticky .card {
                position: static;
                align-self: auto;
            }
        }
    </style>


    <div class="container py-4">
        @if ($errors->any())
            <div class="alert alert-danger">
                <h5 class="alert-heading">Please correct the following errors:</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4" style="background-color: white;">
                        <div class="card-header bg-white">
                            <h4 class="mb-0">Shipping Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('firstName') is-invalid @enderror"
                                        id="firstName" name="firstName"
                                        value="{{ old('firstName', $defaultData['firstName']) }}" required>
                                    @error('firstName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                                        id="lastName" name="lastName"
                                        value="{{ old('lastName', $defaultData['lastName']) }}" required>
                                    @error('lastName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone', $defaultData['phone']) }}"
                                    required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Shipping Address *</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3"
                                    required>{{ old('address', $defaultData['address']) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City *</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                        id="city" name="city" value="{{ old('city', $defaultData['city']) }}"
                                        required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="zip" class="form-label">ZIP Code *</label>
                                    <input type="text" class="form-control @error('zip') is-invalid @enderror"
                                        id="zip" name="zip" value="{{ old('zip', $defaultData['zip']) }}"
                                        required>
                                    @error('zip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="country" class="form-label">Country *</label>
                                    <select class="form-select @error('country') is-invalid @enderror" id="country"
                                        name="country" required>
                                        <option value="Indonesia"
                                            {{ old('country', $defaultData['country']) == 'Indonesia' ? 'selected' : '' }}>
                                            Indonesia</option>
                                        <option value="Malaysia"
                                            {{ old('country', $defaultData['country']) == 'Malaysia' ? 'selected' : '' }}>
                                            Malaysia</option>
                                        <option value="Singapore"
                                            {{ old('country', $defaultData['country']) == 'Singapore' ? 'selected' : '' }}>
                                            Singapore
                                        </option>
                                        <option value="Philippines"
                                            {{ old('country', $defaultData['country']) == 'Philippines' ? 'selected' : '' }}>
                                            Philippines
                                        </option>
                                        <option value="Myanmar"
                                            {{ old('country', $defaultData['country']) == 'Myanmar' ? 'selected' : '' }}>
                                            Myanmar
                                        </option>
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @auth
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="saveAddress" name="saveAddress" checked>
                                    <label class="form-check-label" for="saveAddress">
                                        Save this address for future use
                                    </label>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <div class="card shadow-sm mb-4" style="background-color: white;">
                        <div class="card-header bg-white">
                            <h4 class="mb-0">Notes to Seller</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="sellerNotes" class="form-label">
                                    Special instructions or requests for the seller
                                    <span class="text-muted">(optional)</span>
                                </label>
                                <textarea class="form-control @error('sellerNotes') is-invalid @enderror" id="sellerNotes" name="sellerNotes"
                                    rows="3" placeholder="e.g. Please pack carefully, gift wrapping needed, specific delivery instructions">{{ old('sellerNotes') }}</textarea>
                                @error('sellerNotes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted float-end"><span id="notesCounter">0</span>/200</small>
                                <div class="form-text">
                                    Let us know if you have any special requests for your order.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm" style="background-color: white;">
                        <div class="card-header bg-white">
                            <h4 class="mb-0">Payment Method *</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('paymentMethod') is-invalid @enderror"
                                        type="radio" name="paymentMethod" id="creditCard" value="creditCard"
                                        {{ old('paymentMethod', 'creditCard') == 'creditCard' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="creditCard">
                                        <i class="fab fa-cc-visa me-2"></i>Credit Card
                                    </label>
                                </div>
                                <div id="creditCardForm"
                                    class="mt-3 {{ old('paymentMethod', 'creditCard') != 'creditCard' ? 'd-none' : '' }}">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="cardNumber" class="form-label">Card Number</label>
                                            <input type="text" class="form-control" id="cardNumber"
                                                placeholder="1234 5678 9012 3456">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="expiryDate" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control" id="expiryDate"
                                                placeholder="MM/YY">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="cvv" placeholder="123">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="cardName" class="form-label">Name on Card</label>
                                            <input type="text" class="form-control" id="cardName"
                                                placeholder="John Doe">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('paymentMethod') is-invalid @enderror"
                                        type="radio" name="paymentMethod" id="paypal" value="paypal"
                                        {{ old('paymentMethod') == 'paypal' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="paypal">
                                        <i class="fab fa-paypal me-2"></i>PayPal
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('paymentMethod') is-invalid @enderror"
                                        type="radio" name="paymentMethod" id="bankTransfer" value="bankTransfer"
                                        {{ old('paymentMethod') == 'bankTransfer' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bankTransfer">
                                        <i class="fas fa-university me-2"></i>Bank Transfer
                                    </label>
                                </div>
                            </div>
                            @error('paymentMethod')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-4 order-summary-sticky">
                    <div class="card shadow-sm" style="background-color: white;">
                        <div class="card-header bg-white">
                            <h4 class="mb-0">Order Summary</h4>
                        </div>
                        <div class="card-body">
                            <div class="order-items mb-3">
                                @foreach ($filteredItems as $item)
                                    <div class="d-flex justify-content-between mb-2" data-id="{{ $item['id'] }}">
                                        <div>
                                            {{ $item['name'] }}
                                            <span class="text-muted">x{{ $item['quantity'] }}</span>
                                        </div>
                                        <div>Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <hr>
                            <div class="order-totals mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax (10%):</span>
                                    <span>Rp{{ number_format($tax, 0, ',', '.') }}</span>
                                </div>
                                @if ($voucherDiscount > 0)
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Voucher Discount:</span>
                                        <span>- Rp{{ number_format($voucherDiscount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input @error('termsAgreement') is-invalid @enderror"
                                    type="checkbox" id="termsAgreement" name="termsAgreement" value="1"
                                    {{ old('termsAgreement') ? 'checked' : '' }}>
                                <label class="form-check-label" for="termsAgreement">
                                    I agree to the <a href="{{ route('terms') }}" target="_blank">Terms and
                                        Conditions</a> *
                                </label>
                                @error('termsAgreement')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <input type="hidden" name="selected_items" id="selected-items">
                            <button type="submit" class="btn btn-primary w-100 py-3">
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endsection

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Checkout form JavaScript loading...');

                // Safely get DOM elements with null checks
                const checkoutForm = document.getElementById('checkout-form');
                const hiddenInput = document.getElementById('selected-items');
                const termsCheckbox = document.getElementById('termsAgreement');
                const notesTextarea = document.getElementById('sellerNotes');
                const notesCounter = document.getElementById('notesCounter');
                const paymentRadios = document.querySelectorAll('input[name="paymentMethod"]');
                const creditCardForm = document.getElementById('creditCardForm');

                // Check if essential elements exist
                if (!checkoutForm) {
                    console.error('Checkout form not found');
                    return;
                }

                console.log('Checkout form found:', checkoutForm);

                if (!hiddenInput) {
                    console.error('Hidden input for selected items not found');
                    return;
                }

                console.log('Hidden input found:', hiddenInput);

                // Initialize selected items - Fix the PHP syntax issue
                try {
                    // Get the filtered items data from PHP
                    @if (isset($filteredItems) && !empty($filteredItems))
                        const selectedProductIds = {!! json_encode(array_column($filteredItems, 'id')) !!};
                        console.log('Selected product IDs from PHP:', selectedProductIds);

                        if (selectedProductIds && selectedProductIds.length > 0) {
                            hiddenInput.value = JSON.stringify(selectedProductIds);
                            console.log("Selected items initialized:", hiddenInput.value);
                        } else {
                            console.warn('No selected product IDs found');
                            hiddenInput.value = JSON.stringify([]);
                        }
                    @else
                        console.warn('No filtered items available');
                        hiddenInput.value = JSON.stringify([]);
                    @endif
                } catch (error) {
                    console.error('Error initializing selected items:', error);
                    hiddenInput.value = JSON.stringify([]);
                }

                // Form submission handler - CRITICAL FIX
                if (checkoutForm) {
                    checkoutForm.addEventListener('submit', function(event) {
                        console.log('Form submission triggered');

                        // Check terms agreement first
                        if (!termsCheckbox || !termsCheckbox.checked) {
                            event.preventDefault();
                            console.log('Terms checkbox not checked');
                            alert('You must agree to the Terms and Conditions to proceed.');
                            return false;
                        }

                        // Check selected items
                        const selectedItemsValue = hiddenInput.value;
                        console.log('Selected items value:', selectedItemsValue);

                        if (!selectedItemsValue || selectedItemsValue === '[]' || selectedItemsValue === '') {
                            event.preventDefault();
                            console.log('No items selected');
                            alert('Please select at least one item to proceed to checkout.');
                            return false;
                        }

                        // Validate payment method
                        const selectedPaymentMethod = document.querySelector(
                            'input[name="paymentMethod"]:checked');
                        if (!selectedPaymentMethod) {
                            event.preventDefault();
                            console.log('No payment method selected');
                            alert('Please select a payment method.');
                            return false;
                        }

                        // If credit card is selected, validate credit card fields
                        if (selectedPaymentMethod.value === 'creditCard') {
                            const cardNumber = document.getElementById('cardNumber');
                            const expiryDate = document.getElementById('expiryDate');
                            const cvv = document.getElementById('cvv');
                            const cardName = document.getElementById('cardName');

                            if (!cardNumber || !cardNumber.value.trim() ||
                                !expiryDate || !expiryDate.value.trim() ||
                                !cvv || !cvv.value.trim() ||
                                !cardName || !cardName.value.trim()) {
                                event.preventDefault();
                                console.log('Credit card fields incomplete');
                                alert('Please fill in all credit card details.');
                                return false;
                            }
                        }

                        // Basic form validation
                        const requiredFields = checkoutForm.querySelectorAll(
                            'input[required], select[required], textarea[required]');
                        let isValid = true;

                        requiredFields.forEach(field => {
                            if (!field.value.trim()) {
                                field.classList.add('is-invalid');
                                isValid = false;
                            } else {
                                field.classList.remove('is-invalid');
                            }
                        });

                        if (!isValid) {
                            event.preventDefault();
                            console.log('Required fields not filled');
                            alert('Please fill in all required fields.');
                            return false;
                        }

                        // If we reach here, allow form submission
                        console.log("Form validation passed. Submitting form...");
                        console.log("Form action:", checkoutForm.action);
                        console.log("Form method:", checkoutForm.method);

                        // Show loading state
                        const submitButton = checkoutForm.querySelector('button[type="submit"]');
                        if (submitButton) {
                            submitButton.disabled = true;
                            submitButton.innerHTML =
                                '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
                        }

                        // Allow form to submit normally
                        return true;
                    });
                }

                // Notes character counter
                if (notesTextarea && notesCounter) {
                    const updateCounter = function() {
                        const count = notesTextarea.value.length;
                        notesCounter.textContent = count;

                        if (count > 200) {
                            notesCounter.classList.add('text-danger');
                            notesCounter.classList.remove('text-success');
                        } else {
                            notesCounter.classList.remove('text-danger');
                            notesCounter.classList.add('text-success');
                        }
                    };

                    updateCounter();
                    notesTextarea.addEventListener('input', updateCounter);
                }

                // Payment method toggle
                if (paymentRadios.length > 0 && creditCardForm) {
                    paymentRadios.forEach(radio => {
                        radio.addEventListener('change', function() {
                            console.log('Payment method changed to:', this.value);

                            if (this.id === 'creditCard' && this.checked) {
                                creditCardForm.classList.remove('d-none');
                            } else if (this.checked) {
                                creditCardForm.classList.add('d-none');
                            }
                        });
                    });

                    // Initialize credit card form visibility
                    const selectedPayment = document.querySelector('input[name="paymentMethod"]:checked');
                    if (selectedPayment && selectedPayment.id === 'creditCard') {
                        creditCardForm.classList.remove('d-none');
                    } else {
                        creditCardForm.classList.add('d-none');
                    }
                }

                // Add input formatting
                const phoneInput = document.getElementById('phone');
                if (phoneInput) {
                    phoneInput.addEventListener('input', function() {
                        let value = this.value.replace(/\D/g, '');
                        if (value.length > 15) {
                            value = value.substring(0, 15);
                        }
                        this.value = value;
                    });
                }

                const zipInput = document.getElementById('zip');
                if (zipInput) {
                    zipInput.addEventListener('input', function() {
                        this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
                    });
                }

                console.log('Checkout form JavaScript initialized successfully');
            });
        </script>
    @endsection
