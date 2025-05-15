@extends('layouts.app')

@section('title', 'Shopping Cart - Chile Mart')

@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-20 end-0 m-3 shadow-lg z-3"
        role="alert" style="min-width: 300px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg z-3"
        role="alert" style="min-width: 300px;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <h1 class="fw-bold">Shopping Cart</h1>
            @if(count($cartItems) == 0)
                <div class="alert alert-info">
                    Your cart is empty. <a href="{{ route('products') }}">Browse products</a> to add items.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $index => $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('images/products-img/' . $item['image']) }}"
                                             alt="{{ $item['name'] }}"
                                             class="img-thumbnail me-3"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <h5 class="mb-1">{{ $item['name'] }}</h5>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="price-column">Rp{{ number_format($item['price'], 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <input type="number" 
                                               class="form-control quantity-input" 
                                               value="{{ $item['quantity'] }}" 
                                               min="1" 
                                               data-index="{{ $index }}"
                                               data-price="{{ $item['price'] }}" 
                                               style="width: 80px;">
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="price-column" id="item-total-{{ $index }}">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <form action="{{ route('cart.remove', ['productId' => $item['id']]) }}" method="POST">
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

                <div class="col-md-6">
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">Order Summary</h5>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal-display">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span id="shipping-display">Rp{{ number_format($shippingFee, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span id="tax-display">Rp{{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span id="total-display">Rp{{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('checkout') }}" class="btn btn-primary w-100 mt-3">
                                Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Script --}}
<script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('input', updateCart);
    });

    function formatRupiah(angka) {
        return 'Rp' + angka.toLocaleString('id-ID');
    }

    function updateCart() {
        let subtotal = 0;
        const shipping = 5000;

        document.querySelectorAll('.quantity-input').forEach(input => {
            const index = input.getAttribute('data-index');
            const price = parseInt(input.getAttribute('data-price')) || 0;
            const qty = parseInt(input.value) || 0;
            const total = price * qty;

            // Update total per item
            const itemTotalEl = document.getElementById(`item-total-${index}`);
            if (itemTotalEl) {
                itemTotalEl.textContent = formatRupiah(total);
            }

            subtotal += total;
        });

        const tax = Math.round(subtotal * 0.1);
        const grandTotal = subtotal + tax + shipping;

        document.getElementById('subtotal-display').textContent = formatRupiah(subtotal);
        document.getElementById('shipping-display').textContent = formatRupiah(shipping);
        document.getElementById('tax-display').textContent = formatRupiah(tax);
        document.getElementById('total-display').textContent = formatRupiah(grandTotal);
    }

    updateCart(); // Initial run on page load
</script>

{{-- Style --}}
<style>
    .price-column {
        display: inline-block;
        width: 120px;
        text-align: right;
        font-variant-numeric: tabular-nums;
        white-space: nowrap;
    }

    .quantity-input {
        height: 40px;
        text-align: center;
    }
</style>
@endsection
