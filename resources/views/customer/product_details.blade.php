@extends('layouts.app')

@section('title', $product->name . ' - Chile Mart')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-5">
            <img src="{{ asset('images/products/' . $product->image) }}"
                 alt="{{ $product->name }}"
                 class="img-fluid rounded shadow-sm">
        </div>
        <div class="col-md-7">
            <h1 class="fw-bold">{{ $product->name }}</h1>
            <p class="text-muted">Category: {{ $product->category }}</p>
            <h3 class="text-primary">${{ number_format($product->price, 2) }}</h3>

            <p class="mt-4">{{ $product->description }}</p>

            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
                @csrf
                <div class="input-group mb-3" style="width: 140px;">
                    <input type="number" name="quantity" value="1" min="1" class="form-control text-center">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
