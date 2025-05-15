@extends('layouts.app')

@section('title', 'Your Wishlist')

@section('content')

<div class="container">
    <h1 class="fw-bold mb-4">Your Wishlist</h1>

    <!-- Display success/error messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        @foreach($wishlistItems as $items)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="text-center p-3">
                        <img src="{{ asset('images/products-img/' . $items['image']) }}" alt="{{ $items['product_name'] }}" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title fw-semibold">{{ $items['product_name'] }}</h5>
                        {{-- <p class="card-text text-muted mb-2">{{ $items['category'] }}</p> --}}
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <h5 class="text-primary mb-0">Rp {{ number_format($items['price'], 0, ',', '.') }}</h5>
                            <!-- Add to Wishlist Button -->
                            <form action="{{ route('wishlist.add', $items['product_id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">Add to Wishlist</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <h2 class="fw-bold mt-5">Wishlist Items</h2>
    <div class="row">
        @forelse(session('wishlist', []) as $item)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="text-center p-3">
                        <img src="{{ asset('images/products-img/' . $item['image']) }}" alt="{{ $item['name'] }}" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title fw-semibold">{{ $item['name'] }}</h5>
                        <p class="card-text text-muted mb-2">{{ $item['category'] }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <h5 class="text-primary mb-0">Rp {{ number_format($item['price'], 0, ',', '.') }}</h5>

                            <!-- Remove from Wishlist Button -->
                            <form action="{{ route('wishlist.remove', $item['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">Remove from Wishlist</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p>No products in your wishlist. Start adding some!</p>
            </div>
        @endforelse
    </div>
</div>

@endsection
