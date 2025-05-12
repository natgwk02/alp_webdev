@extends('layouts.app')

@section('title', $product['name'] . ' - Chile Mart')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('images/products/' . $product['image']) }}" class="img-fluid rounded" alt="{{ $product['name'] }}">
        </div>
        <div class="col-md-6">
            <h2 class="fw-bold">{{ $product['name'] }}</h2>
            <p class="text-muted">{{ $product['category'] }} | {{ $product['weight'] }} | Origin: {{ $product['origin'] }}</p>
            <h4 class="text-primary">${{ number_format($product['price'], 2) }}</h4>
            <p class="mt-3">{{ $product['description'] }}</p>

            <div class="mt-4 d-flex gap-2">
                <a href="#" class="btn btn-primary">Add to Cart</a>
                <a href="#" class="btn btn-outline-secondary">Add to Wishlist</a>
            </div>

            <div class="mt-4">
                <h5>Nutrition Facts</h5>
                <ul>
                    <li>Calories: {{ $product['nutrition']['calories'] }}</li>
                    <li>Protein: {{ $product['nutrition']['protein'] }}</li>
                    <li>Fat: {{ $product['nutrition']['fat'] }}</li>
                </ul>
            </div>

            <div class="mt-4">
                <h5>Customer Reviews</h5>
                @foreach ($product['reviews'] as $review)
                    <div class="border rounded p-2 mb-2">
                        <strong>{{ $review['user'] }}</strong> ({{ $review['rating'] }}/5)<br>
                        <span>{{ $review['comment'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
