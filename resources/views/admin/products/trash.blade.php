@extends('layouts.admin')

@section('title', 'Trashed Products - Chile Mart Admin')

@section('content')

    <style>
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }

        .table-responsive {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .status-badge {
            width: 100px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            margin-right: 5px;
        }

        .search-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>

    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="text-decoration-none text-secondary">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('admin.products') }}"
                                        class="text-decoration-none text-secondary">Products</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Trash</li>
                            </ol>
                        </nav>
                        <h1 class="fw-bold mb-2 mb-md-0">Trashed Products</h1>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Table --}}
        <div class="table-responsive mb-4">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" width="50">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </div>
                        </th>
                        <th scope="col">Product</th>
                        <th scope="col">Category</th>
                        <th scope="col">Price</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Status</th>
                        <th scope="col">Deleted Date</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trashedProducts as $product)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $product->products_image ? asset('images/products-img/' . $product->products_image) : 'https://via.placeholder.com/60' }}"
                                        alt="{{ $product->products_name }}" class="product-img me-3">
                                    <div>
                                        <h6 class="mb-0">{{ $product->products_name }}</h6>
                                        <small class="text-muted">#{{ $product->products_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->category ? $product->category->categories_name : 'N/A' }}</td>
                            <td>Rp {{ number_format($product->unit_price, 0, ',', '.') }}</td>
                            <td>{{ $product->products_stock }}</td>
                            <td>
                                <span class="badge bg-danger status-badge">Trashed</span>
                            </td>
                            <td>{{ $product->updated_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <form action="{{ route('admin.products.restore', ['product' => $product]) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success action-btn"
                                        title="Restore">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">The trash is empty.</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                @if ($trashedProducts->total() > 0)
                    <p class="mb-0">Showing {{ $trashedProducts->firstItem() }} to
                        {{ $trashedProducts->lastItem() }} dari {{ $trashedProducts->total() }} entry</p>
                @else
                    <p class="mb-0">No entry found</p>
                @endif
            </div>
            <nav aria-label="Page navigation">
                @if ($trashedProducts->hasPages())
                    <ul class="pagination mb-0">
                        {{-- Tombol Previous --}}
                        <li class="page-item {{ $trashedProducts->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $trashedProducts->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        @for ($i = 1; $i <= $trashedProducts->lastPage(); $i++)
                            <li class="page-item {{ $i == $trashedProducts->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $trashedProducts->url($i) }}">{{ $i }}</a>  {{-- URL untuk halaman ke-i --}}
                            </li>
                        @endfor

                        {{-- Tombol Next --}}
                        <li
                            class="page-item {{ $trashedProducts->currentPage() == $trashedProducts->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $trashedProducts->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                @endif
            </nav>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Select all
            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('tbody .form-check-input');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        </script>
    @endsection
