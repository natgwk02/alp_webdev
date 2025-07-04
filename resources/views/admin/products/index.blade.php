@extends('layouts.admin')

@section('title', 'Manage Products - Chile Mart Admin')

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

        #editProductForm.data-loading {
            position: relative;
        }

        #editProductForm.data-loading::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50' viewBox='0 0 50 50'%3E%3Cpath fill='%23007bff' d='M25,5A20,20 0 0,1 45,25C45,25 45,26 44,26C43,26 43,25 43,25A18,18 0 0,0 25,7C12.83,7 3,16.83 3,29A18,18 0 0,0 21,47C21,47 21,48 22,48C23,48 23,47 23,47A20,20 0 0,1 5,27A20,20 0 0,1 25,5Z'%3E%3CanimateTransform attributeName='transform' type='rotate' from='0 25 25' to='360 25 25' dur='0.8s' repeatCount='indefinite'/%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
        }

        #editProductForm.data-loading .form-control:disabled,
        #editProductForm.data-loading .form-select:disabled {
            background-color: #f8f9fa;
            opacity: 0.7;
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
                                <li class="breadcrumb-item active" aria-current="page">Products</li>
                            </ol>
                        </nav>
                        <h1 class="fw-bold mb-2 mb-md-0">Products Management</h1>
                    </div>
                    <div class="d-flex align-items-center gap-2 mt-3 mt-md-0">
                        <a href="{{ route('admin.products.trash') }}" class="btn btn-outline-secondary mt-5">
                            <i class="fas fa-trash"></i> View Trash
                        </a>
                        <button class="btn btn-primary mt-5" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="bi bi-plus-lg"></i> Add New Product
                        </button>
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

        {{-- search n filter --}}
        <form action="{{ route('admin.products') }}" method="GET">
            <div class="search-container mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchInput" name="search" class="form-control border-start-0"
                                placeholder="Search products..." value="{{ $current_search ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="categorySelect" name="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $id => $name)
                                <option value="{{ $id }}"
                                    {{ isset($current_category) && $current_category == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="statusSelect" name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="In Stock"
                                {{ isset($current_status) && $current_status == 'In Stock' ? 'selected' : '' }}>In Stock
                            </option>
                            <option value="Low Stock"
                                {{ isset($current_status) && $current_status == 'Low Stock' ? 'selected' : '' }}>Low Stock
                            </option>
                            <option value="Out of Stock"
                                {{ isset($current_status) && $current_status == 'Out of Stock' ? 'selected' : '' }}>Out of
                                Stock</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-3">
                            <button id="filterBtn" type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-filter me-1"></i>Filter Products
                            </button>
                            <a href="{{ route('admin.products') }}" id="resetBtn"
                                class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- table --}}
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
                        <th scope="col">Last Updated</th>
                        <th scope="col" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
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
                                @php
                                    $status = 'In Stock';
                                    $badgeClass = 'bg-success';

                                    $lowStockThreshold = isset($product->low_stock_threshold)
                                        ? $product->low_stock_threshold
                                        : 10;

                                    if ($product->products_stock == 0) {
                                        $status = 'Out of Stock';
                                        $badgeClass = 'bg-secondary';
                                    } elseif ($product->products_stock < $lowStockThreshold) {
                                        $status = 'Low Stock';
                                        $badgeClass =
                                            $product->products_stock < $lowStockThreshold / 2
                                                ? 'bg-danger'
                                                : 'bg-warning text-dark';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} status-badge">{{ $status }}</span>
                            </td>
                            <td>{{ $product->updated_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal"
                                    data-bs-target="#editProductModal" data-product-id="{{ $product->products_id }}">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger action-btn"
                                    data-bs-toggle="modal" data-bs-target="#deleteProductModal"
                                    data-product-id="{{ $product->products_id }}"
                                    data-product-name="{{ $product->products_name }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                @if ($products->total() > 0)
                    <p class="mb-0">Showing {{ $products->firstItem() }} to
                        {{ $products->lastItem() }} to {{ $products->total() }} entries</p>
                @else
                    <p class="mb-0">No entry found</p>
                @endif
            </div>
            <nav aria-label="Page navigation">
                @if ($products->hasPages())
                    <ul class="pagination mb-0">
                        {{-- Tombol Before --}}
                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        @for ($i = 1; $i <= $products->lastPage(); $i++)
                            <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        {{-- Tombol Next --}}
                        <li class="page-item {{ $products->currentPage() == $products->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                @endif
            </nav>
        </div>

        {{-- modals --}}
        {{-- Add Product --}}
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        @if ($errors->any() && old('form_type') === 'add_product')
                            <div class="alert alert-danger">
                                <strong>Whoops! Please fix these errors:</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Add Form --}}
                        <form id="addProductForm" action="{{ route('admin.products.create') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="form_type" value="add_product">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="add_products_name" class="form-label">Product Name*</label>
                                    <input type="text"
                                        class="form-control @error('products_name') is-invalid @enderror"
                                        id="add_products_name" name="products_name" value="{{ old('products_name') }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="add_categories_id" class="form-label">Category*</label>
                                    <select class="form-select @error('categories_id') is-invalid @enderror"
                                        id="add_categories_id" name="categories_id" required>
                                        <option value="" selected disabled>Select category</option>
                                        @foreach ($categories as $id => $name)
                                            <option value="{{ $id }}"
                                                {{ old('categories_id') == $id ? 'selected' : '' }}>{{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="add_unit_price" class="form-label">Price (Rp)*</label>
                                    <input type="number" class="form-control @error('unit_price') is-invalid @enderror"
                                        id="add_unit_price" name="unit_price" step="1" min="0"
                                        value="{{ old('unit_price') }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="add_orders_price" class="form-label">Orders Price (Rp)*</label>
                                    <input type="number" class="form-control @error('orders_price') is-invalid @enderror"
                                        id="add_orders_price" name="orders_price" step="1" min="0"
                                        value="{{ old('orders_price') }}">
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="add_products_stock" class="form-label">Stock Quantity*</label>
                                    <input type="number"
                                        class="form-control @error('products_stock') is-invalid @enderror"
                                        id="add_products_stock" name="products_stock" min="0"
                                        value="{{ old('products_stock') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="add_low_stock_threshold" class="form-label">Low Stock Threshold</label>
                                    <input type="number"
                                        class="form-control @error('low_stock_threshold') is-invalid @enderror"
                                        id="add_low_stock_threshold" name="low_stock_threshold" min="1"
                                        value="{{ old('low_stock_threshold', 10) }}" placeholder="Default: 10">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="add_products_description" class="form-label">Description</label>
                                <textarea class="form-control @error('products_description') is-invalid @enderror" id="add_products_description"
                                    name="products_description" rows="3">{{ old('products_description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="add_products_image" class="form-label">Product Image*</label>
                                <input class="form-control @error('products_image') is-invalid @enderror" type="file"
                                    id="add_products_image" name="products_image" required>
                            </div>

                            <div class="mb-3">
                                <label for="add_hover_image" class="form-label">Hover Image*</label>
                                <input class="form-control @error('products_image') is-invalid @enderror" type="file"
                                    id="add_hover_image" name="hover_image" required>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="addProductForm" class="btn btn-primary">Add Product</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Product --}}
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editProductForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editProductName" class="form-label">Product Name*</label>
                                    <input type="text" class="form-control" id="editProductName" name="products_name"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editProductCategory" class="form-label">Category*</label>
                                    <select class="form-select" id="editProductCategory" name="categories_id" required>
                                        <option value="" disabled>Select category</option>
                                        @foreach ($categories as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editProductPrice" class="form-label">Price (Rp)*</label>
                                    <input type="number" class="form-control" id="editProductPrice" name="unit_price"
                                        step="1" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editOrdersPrice" class="form-label">Orders Price (Rp)*</label>
                                    <input type="number" class="form-control" id="editOrdersPrice" name="orders_price"
                                        step="1" min="0">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editProductStock" class="form-label">Stock Quantity*</label>
                                    <input type="number" class="form-control" id="editProductStock"
                                        name="products_stock" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editLowStockThreshold" class="form-label">Low Stock Threshold*</label>
                                    <input type="number" class="form-control" id="editLowStockThreshold"
                                        name="low_stock_threshold" min="1"
                                        value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 10) }}"
                                        required>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label for="editProductDescription" class="form-label">Description</label>
                                    <textarea class="form-control" id="editProductDescription" name="products_description" rows="3"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Product Image (Upload new to replace)</label>
                                    <div class="d-flex align-items-center mb-2">
                                        <div id="productImageLoading"
                                            style="width:100px;height:100px;display:flex;align-items:center;justify-content:center;">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <img src="" id="currentProductImage" alt="Current product image"
                                            class="me-3 rounded"
                                            style="width:100px;height:100px;object-fit:cover;display:none;"
                                            onerror="this.onerror=null;this.src='https://via.placeholder.com/100?text=Image+Error'">
                                        <span class="text-muted">Current image</span>
                                    </div>
                                    <input class="form-control" type="file" name="products_image">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Hover Image (Upload new to replace)</label>
                                    <div class="d-flex align-items-center mb-2">
                                        <div id="hoverImageLoading"
                                            style="width:100px;height:100px;display:flex;align-items:center;justify-content:center;">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                        <img src="" id="currentHoverImage" alt="Current hover image"
                                            class="me-3 rounded"
                                            style="width:100px;height:100px;object-fit:cover;display:none;"
                                            onerror="this.onerror=null;this.src='https://via.placeholder.com/100?text=Image+Error'">
                                        <span class="text-muted">Current image</span>
                                    </div>
                                    <input class="form-control" type="file" name="hover_image">
                                </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="editProductForm" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Select all checkbox functionality
            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('tbody .form-check-input');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            const productCache = {};

            // Fetch product data when hovering over edit button
            document.querySelectorAll('[data-bs-toggle="modal"][data-bs-target="#editProductModal"]').forEach(button => {
                button.addEventListener('mouseenter', function() {
                    const productId = this.getAttribute('data-product-id');
                    if (!productCache[productId]) {
                        fetch(`/admin/products/${productId}/edit-data`, {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(product => {
                                productCache[productId] = product;
                                if (product.products_image) {
                                    const img = new Image();
                                    img.src = '{{ asset('images/products-img') }}/' + product
                                        .products_image;
                                }
                                if (product.hover_image) {
                                    const img = new Image();
                                    img.src = '{{ asset('images/products-img') }}/' + product.hover_image;
                                }
                            })
                            .catch(error => console.error('Prefetch error:', error));
                    }
                });
            });

            // Edit Product Modal
            const editProductModal = document.getElementById('editProductModal');
            if (editProductModal) {
                let currentProductId = null;
                let abortController = null;

                editProductModal.addEventListener('show.bs.modal', async function(event) {
                    const button = event.relatedTarget;
                    const productId = button.getAttribute('data-product-id');
                    currentProductId = productId;

                    if (abortController) {
                        abortController.abort();
                    }
                    abortController = new AbortController();

                    // Clear all fields immediately
                    document.getElementById('editProductName').value = '';
                    document.getElementById('editProductCategory').selectedIndex = 0;
                    document.getElementById('editProductPrice').value = '';
                    document.getElementById('editOrdersPrice').value = '';
                    document.getElementById('editProductStock').value = '';
                    document.getElementById('editLowStockThreshold').value = '10';
                    document.getElementById('editProductDescription').value = '';
                    document.getElementById('currentProductImage').style.display = 'none';
                    document.getElementById('currentHoverImage').style.display = 'none';
                    document.getElementById('productImageLoading').style.display = 'flex';
                    document.getElementById('hoverImageLoading').style.display = 'flex';

                    document.getElementById('editProductForm').classList.add('data-loading');
                    document.querySelectorAll('#editProductForm .form-control, #editProductForm .form-select')
                        .forEach(el => {
                            el.setAttribute('disabled', 'disabled');
                        });

                    try {
                        const response = await fetch(`/admin/products/${productId}/edit-data?_=${Date.now()}`, {
                            signal: abortController.signal,
                            headers: {
                                'Cache-Control': 'no-store',
                                'Pragma': 'no-cache'
                            },
                            cache: 'no-store'
                        });

                        if (!response.ok) throw new Error('Failed to fetch product data');
                        const product = await response.json();

                        if (currentProductId !== productId) return;

                        // Mengisi form fields with fetched data
                        document.getElementById('editProductForm').action = `/admin/products/${productId}/update`;
                        document.getElementById('editProductName').value = product.products_name || '';
                        document.getElementById('editProductCategory').value = product.categories_id || '';
                        document.getElementById('editProductPrice').value = product.unit_price || '';
                        document.getElementById('editOrdersPrice').value = product.orders_price || '';
                        document.getElementById('editProductStock').value = product.products_stock || '';
                        document.getElementById('editLowStockThreshold').value = product.low_stock_threshold || 10;
                        document.getElementById('editProductDescription').value = product.products_description ||
                            '';

                        if (product.products_image) {
                            const productImg = document.getElementById('currentProductImage');
                            productImg.src = `/images/products-img/${product.products_image}?_=${Date.now()}`;
                            productImg.onload = () => {
                                document.getElementById('productImageLoading').style.display = 'none';
                                productImg.style.display = 'block';
                            };
                            productImg.onerror = () => {
                                productImg.src = 'https://via.placeholder.com/100?text=Image+Error';
                                document.getElementById('productImageLoading').style.display = 'none';
                                productImg.style.display = 'block';
                            };
                        } else {
                            document.getElementById('currentProductImage').src =
                                'https://via.placeholder.com/100?text=No+Image';
                            document.getElementById('productImageLoading').style.display = 'none';
                            document.getElementById('currentProductImage').style.display = 'block';
                        }

                        if (product.hover_image) {
                            const hoverImg = document.getElementById('currentHoverImage');
                            hoverImg.src = `/images/hoverproducts-img/${product.hover_image}?_=${Date.now()}`;
                            hoverImg.onload = () => {
                                document.getElementById('hoverImageLoading').style.display = 'none';
                                hoverImg.style.display = 'block';
                            };
                            hoverImg.onerror = () => {
                                hoverImg.src = 'https://via.placeholder.com/100?text=Image+Error';
                                document.getElementById('hoverImageLoading').style.display = 'none';
                                hoverImg.style.display = 'block';
                            };
                        } else {
                            document.getElementById('currentHoverImage').src =
                                'https://via.placeholder.com/100?text=No+Hover+Image';
                            document.getElementById('hoverImageLoading').style.display = 'none';
                            document.getElementById('currentHoverImage').style.display = 'block';
                        }

                        document.getElementById('editProductForm').classList.remove('data-loading');
                        document.querySelectorAll('#editProductForm .form-control, #editProductForm .form-select')
                            .forEach(el => {
                                el.removeAttribute('disabled');
                            });

                    } catch (error) {
                        if (error.name !== 'AbortError') {
                            console.error('Error:', error);
                            document.getElementById('currentProductImage').src =
                                'https://via.placeholder.com/100?text=Error';
                            document.getElementById('productImageLoading').style.display = 'none';
                            document.getElementById('currentProductImage').style.display = 'block';

                            document.getElementById('currentHoverImage').src =
                                'https://via.placeholder.com/100?text=Error';
                            document.getElementById('hoverImageLoading').style.display = 'none';
                            document.getElementById('currentHoverImage').style.display = 'block';

                            document.getElementById('editProductForm').classList.remove('data-loading');
                            document.querySelectorAll(
                                '#editProductForm .form-control, #editProductForm .form-select').forEach(el => {
                                el.removeAttribute('disabled');
                            });
                        }
                    }
                });

                // Clean up on modal close
                editProductModal.addEventListener('hidden.bs.modal', function() {
                    if (abortController) {
                        abortController.abort();
                        abortController = null;
                    }
                    currentProductId = null;
                });
            }

            // Delete Product Modal
            const deleteProductModal = document.getElementById('deleteProductModal');
            deleteProductModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-product-id');
                const productName = button.getAttribute('data-product-name');

                document.getElementById('deleteProductName').textContent = productName;
                const form = document.getElementById('deleteProductForm');
                form.action = `/admin/products/delete/${productId}`;
            });

            // Search and filter
            const resetBtn = document.getElementById('resetBtn');
            resetBtn.addEventListener('click', function() {
                document.getElementById('searchInput').value = '';
                document.getElementById('categorySelect').selectedIndex = 0;
                document.getElementById('statusSelect').selectedIndex = 0;
            });
        </script>
    @endsection

    {{-- Delete Product --}}
    <form id="deleteProductForm" method="POST">
        @csrf
        @method('DELETE')

        <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteProductModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Product</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
