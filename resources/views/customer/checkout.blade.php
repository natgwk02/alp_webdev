@extends('layouts.app')

@section('title', 'Checkout - Chile Mart')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4" style="background-color: white;">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Shipping Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" value="John" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" value="Doe" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="john.doe@example.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" value="+56912345678" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Shipping Address</label>
                        <textarea class="form-control" id="address" rows="3" required>123 Main St, Santiago, Chile</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" value="Santiago" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="zip" class="form-label">ZIP Code</label>
                            <input type="text" class="form-control" id="zip" value="8320000" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" required>
                                <option selected>Chile</option>
                                <option>Argentina</option>
                                <option>Peru</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="saveAddress" checked>
                        <label class="form-check-label" for="saveAddress">
                            Save this address for future use
                        </label>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm" style="background-color: white;">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Payment Method</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" checked>
                            <label class="form-check-label" for="creditCard">
                                <i class="fab fa-cc-visa me-2"></i>Credit Card
                            </label>
                        </div>
                        <div id="creditCardForm" class="mt-3">
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
                            <input class="form-check-input" type="radio" name="paymentMethod" id="paypal">
                            <label class="form-check-label" for="paypal">
                                <i class="fab fa-paypal me-2"></i>PayPal
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransfer">
                            <label class="form-check-label" for="bankTransfer">
                                <i class="fas fa-university me-2"></i>Bank Transfer
                            </label>
                        </div>
                    </div>
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
                            <div>Rp{{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                        </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="order-totals mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>Rp{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>Rp{{ number_format($shippingFee, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>Rp{{ number_format($tax, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span>Rp{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="termsAgreement" required>
                        <label class="form-check-label" for="termsAgreement">
                            I agree to the <a href="#">Terms and Conditions</a>
                        </label>
                    </div>

                    <button class="btn btn-primary w-100 py-3" onclick="window.location.href='/orders/1001'">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
