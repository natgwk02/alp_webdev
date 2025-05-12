@extends('base.base')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow-lg z-3" role="alert" style="min-width: 300px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@section('content')
    @can('insert-product')
        <a href="{{ route('product_insert_form') }}" class="btn btn-primary">Insert New Product</a>
    @endcan
    <h1>Ini halaman store</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($products as $product)
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text"><i>{{ $product->product_category->description }}</i></p>
                        <p class="card-text">{{ $product->price }}</p>
                        <p class="card-text">{{ $product->details }}</p>
                            @can('edit-product')
                            <a href="{{ route('product_edit_form', $product->id) }}" class="btn btn-warning">Edit Product</a>
                            @endcan
                            @can('delete-product')
                            <form action="{{ route('delete_product', $product->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure want to delete this product?');" style="display:inline">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-secondary">Delete</button>
                            </form>
                       
                        @endcan
                        <form action="{{ route('add-to-cart', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-cart-shopping"></i>
                            </button> 
                        </form>

                        
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection