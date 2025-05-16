@extends('layouts.app')
@section('title', 'Checkout - Chile Mart')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                
                <div class="card shadow-sm mb-4" style="background-color: white;">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Shipping Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control @error('firstName') is-invalid @enderror" 
                                       id="firstName" name="firstName" value="{{ old('firstName', $defaultData['firstName']) }}" required>
                                @error('firstName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control @error('lastName') is-invalid @enderror" 
                                       id="lastName" name="lastName" value="{{ old('lastName', $defaultData['lastName']) }}" required>
                                @error('lastName')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $defaultData['email']) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $defaultData['phone']) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Shipping Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" required>{{ old('address', $defaultData['address']) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $defaultData['city']) }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="zip" class="form-label">ZIP Code *</label>
                                <input type="text" class="form-control @error('zip') is-invalid @enderror" 
                                       id="zip" name="zip" value="{{ old('zip', $defaultData['zip']) }}" required>
                                @error('zip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="country" class="form-label">Country *</label>
                                <select class="form-select @error('country') is-invalid @enderror" id="country" name="country" required>
                                    <option value="Chile" {{ old('country', $defaultData['country']) == 'Chile' ? 'selected' : '' }}>Chile</option>
                                    <option value="Argentina" {{ old('country', $defaultData['country']) == 'Argentina' ? 'selected' : '' }}>Argentina</option>
                                    <option value="Peru" {{ old('country', $defaultData['country']) == 'Peru' ? 'selected' : '' }}>Peru</option>
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

                <div class="card shadow-sm mb-4" style="background-color: #C1E8FF;">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Notes to Seller</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="sellerNotes" class="form-label">
                                Special instructions or requests for the seller
                                <span class="text-muted">(optional)</span>
                            </label>
                            <textarea class="form-control @error('sellerNotes') is-invalid @enderror" 
                                    id="sellerNotes" name="sellerNotes" rows="3" 
                                    placeholder="e.g. Please pack carefully, gift wrapping needed, specific delivery instructions">{{ old('sellerNotes') }}</textarea>
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
                            <div id="creditCardForm" class="mt-3 {{ old('paymentMethod', 'creditCard') != 'creditCard' ? 'd-none' : '' }}">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="cardNumber" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="expiryDate" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" placeholder="123">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="cardName" class="form-label">Name on Card</label>
                                        <input type="text" class="form-control" id="cardName" placeholder="John Doe">
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

        <div class="col-md-4">
            <div class="card shadow-sm" style="background-color: white;">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Order Summary</h4>
                </div>
                <div class="card-body">
                    <div class="order-items mb-3">
                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
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
        @if($voucherDiscount > 0)
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
            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Terms and Conditions</a> *
        </label>
        @error('termsAgreement')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary w-100 py-3">
        Place Order
    </button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sellerNotes = document.getElementById('sellerNotes');
        const counter = document.getElementById('notesCounter');
        
        if (sellerNotes) {
            counter.textContent = sellerNotes.value.length;
            sellerNotes.addEventListener('input', function () {
                counter.textContent = this.value.length;
            });
        }

        // Show/hide credit card form
        document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const creditCardForm = document.getElementById('creditCardForm');
                if (this.value === 'creditCard') {
                    creditCardForm.classList.remove('d-none');
                } else {
                    creditCardForm.classList.add('d-none');
                }
            });
        });
    });

    // Character counter for notes to seller
    const notesTextarea = document.getElementById('sellerNotes');
    const notesCounter = document.getElementById('notesCounter');
    
    if (notesTextarea && notesCounter) {
        notesTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            notesCounter.textContent = currentLength;
            
            if (currentLength > 200) {
                notesCounter.classList.add('text-danger');
            } else {
                notesCounter.classList.remove('text-danger');
            }
        });
        
        // Initialize counter on page load
        notesCounter.textContent = notesTextarea.value.length;
        if (notesTextarea.value.length > 200) {
            notesCounter.classList.add('text-danger');
        }
    }
</script>
@endsection