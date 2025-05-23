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
                                <li class="breadcrumb-item active" aria-current="page">Products</li>
                            </ol>
                        </nav>
                        <h1 class="fw-bold mb-2 mb-md-0">Products Management</h1>
                    </div>
                    <button class="btn btn-primary mt-5" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="bi bi-plus-lg"></i> Add New Product
                    </button>
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
                            {{-- Add name="search" and set value --}}
                            <input type="text" id="searchInput" name="search" class="form-control border-start-0"
                                placeholder="Search products..." value="{{ $current_search ?? '' }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        {{-- Add name="category" and set selected --}}
                        <select id="categorySelect" name="category" class="form-select">
                            {{-- Change value to empty for 'All Categories' --}}
                            <option value="">All Categories</option>
                            @foreach ($categories as $id => $name)
                                {{-- Check against $current_category --}}
                                <option value="{{ $id }}"
                                    {{ isset($current_category) && $current_category == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        {{-- Add name="status" and set selected --}}
                        <select id="statusSelect" name="status" class="form-select">
                            <option value="">All Status</option>
                            {{-- Use specific values and check against $current_status --}}
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
                            {{-- Change Filter button to type="submit" --}}
                            <button id="filterBtn" type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-filter me-1"></i>Filter Products
                            </button>
                            {{-- Reset button should be a link to the base route --}}
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
                    {{-- Pastikan $products tidak kosong --}}
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Ganti path asset() sesuai lokasi gambar Anda --}}
                                    {{-- Jika di storage/app/public/products -> asset('storage/products/' . $product->products_image) --}}
                                    {{-- Jika di public/images/products-img/ -> asset('images/products-img/' . $product->products_image) --}}
                                    <img src="{{ $product->products_image ? asset('storage/products/' . $product->products_image) : 'https://via.placeholder.com/60' }}"
                                        alt="{{ $product->products_name }}" class="product-img me-3">
                                    <div>
                                        {{-- Gunakan $product->products_name --}}
                                        <h6 class="mb-0">{{ $product->products_name }}</h6>
                                        {{-- Gunakan $product->products_id (atau primary key Anda) --}}
                                        <small class="text-muted">#{{ $product->products_id }}</small>
                                    </div>
                                </div>
                            </td>
                            {{-- Gunakan relationship: $product->category->categories_name --}}
                            <td>{{ $product->category ? $product->category->categories_name : 'N/A' }}</td>
                            {{-- Gunakan $product->unit_price --}}
                            <td>Rp {{ number_format($product->unit_price, 0, ',', '.') }}</td>
                            {{-- Gunakan $product->products_stock --}}
                            <td>{{ $product->products_stock }}</td>
                            <td>
                                {{-- Logika Status (contoh - sesuaikan!) --}}
                                @php
                                    $status = 'In Stock';
                                    $badgeClass = 'bg-success';
                                    if ($product->products_stock == 0) {
                                        $status = 'Out of Stock';
                                        $badgeClass = 'bg-danger';
                                    } elseif ($product->products_stock < 20) {
                                        // Anggap 20 sebagai batas Low Stock
                                        $status = 'Low Stock';
                                        $badgeClass = 'bg-warning text-dark';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} status-badge">{{ $status }}</span>
                            </td>
                            {{-- Gunakan $product->updated_at --}}
                            <td>{{ $product->updated_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                {{-- Pastikan ini men-trigger modal dan JS Anda mengambil data (atau link ke edit page) --}}
                                <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal"
                                    data-bs-target="#editProductModal" data-product-id="{{ $product->products_id }}">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                {{-- Pastikan ini men-trigger modal dan JS Anda set action form --}}
                                <form id="deleteProductForm_{{ $product->products_id }}"
                                    action="{{ route('admin.products.delete', ['product' => $product->products_id]) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger action-btn"
                                        data-bs-toggle="modal" data-bs-target="#deleteProductModal"
                                        data-product-id="{{ $product->products_id }}"
                                        data-product-name="{{ $product->products_name }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        {{-- Tampilkan jika tidak ada produk --}}
                        <tr>
                            <td colspan="8" class="text-center">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                {{-- Pastikan ada produk sebelum menampilkan teks --}}
                @if ($products->total() > 0)
                    <p class="mb-0">Showing {{ $products->firstItem() }} to
                        {{ $products->lastItem() }} dari {{ $products->total() }} entry</p>
                @else
                    <p class="mb-0">No entry found</p>
                @endif
            </div>
            <nav aria-label="Page navigation">
                {{-- Hanya tampilkan navigasi jika ada lebih dari satu halaman --}}
                @if ($products->hasPages())
                    <ul class="pagination mb-0">
                        {{-- Tombol Previous --}}
                        <li class="page-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->previousPageUrl() }}" {{-- URL untuk halaman sebelumnya --}}
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        @for ($i = 1; $i <= $products->lastPage(); $i++)
                            <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                                {{-- URL untuk halaman ke-i --}}
                            </li>
                        @endfor

                        {{-- Tombol Next --}}
                        <li class="page-item {{ $products->currentPage() == $products->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ $products->nextPageUrl() }}" {{-- URL untuk halaman berikutnya --}}
                                aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                @endif
            </nav>
        </div>

        {{-- modals --}}

        <!-- Add Product - Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        {{-- Display Validation Errors Here --}}
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

                        {{-- UPDATE THE FORM TAG --}}
                        <form id="addProductForm" action="{{ route('admin.products.create') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            {{-- Hidden field to identify form on validation error --}}
                            <input type="hidden" name="form_type" value="add_product">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="add_products_name" class="form-label">Product Name*</label>
                                    {{-- Change name, add old() --}}
                                    <input type="text"
                                        class="form-control @error('products_name') is-invalid @enderror"
                                        id="add_products_name" name="products_name" value="{{ old('products_name') }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="add_categories_id" class="form-label">Category*</label>
                                    {{-- Change name, use ID as value, add old() --}}
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
                                    {{-- Change name, add old() --}}
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

                            <div class="col-md-6">
                                <label for="add_products_stock" class="form-label">Stock Quantity*</label>
                                {{-- Change name, add old() --}}
                                <input type="number" class="form-control @error('products_stock') is-invalid @enderror"
                                    id="add_products_stock" name="products_stock" min="0"
                                    value="{{ old('products_stock') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="add_products_description" class="form-label">Description</label>
                                {{-- Change name, add old() --}}
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
                        {{-- Ensure this submits the correct form --}}
                        <button type="submit" form="addProductForm" class="btn btn-primary">Add Product</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Product - Modal -->
        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Display Validation Errors (Optional, needs more setup for AJAX/Modals) --}}

                        {{-- Form action will be set by JS --}}
                        <form id="editProductForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') {{-- Tells Laravel to treat this as a PUT request --}}

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
                                        {{-- Use $categories passed to the index view --}}
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
                                    {{-- Add other fields like Weight if needed --}}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="editProductDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editProductDescription" name="products_description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="editProductImage" class="form-label">Product Image (Upload new to
                                    replace)</label>
                                <div class="d-flex align-items-center mb-2">
                                    <img src="https://via.placeholder.com/100" id="currentProductImage"
                                        alt="Current product image" class="me-3 rounded"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                    <span class="text-muted">Current image</span>
                                </div>
                                {{-- Image input is NOT required for edit --}}
                                <input class="form-control" type="file" id="editProductImage" name="products_image">
                            </div>

                            <div class="mb-3">
                                <label for="editHoverImage" class="form-label">Hover Image (Upload new to
                                    replace)</label>
                                <div class="d-flex align-items-center mb-2">
                                    <img src="https://via.placeholder.com/100" id="currentProductImage"
                                        alt="Current product image" class="me-3 rounded"
                                        style="width: 100px; height: 100px; object-fit: cover;">
                                    <span class="text-muted">Current image</span>
                                </div>
                                {{-- Image input is NOT required for edit --}}
                                <input class="form-control" type="file" id="editHoverImage" name="hover_image">
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

        <!-- Delete Product - Modal -->
        <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteProductModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
                        <p class="text-danger mb-0">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <!-- Form action will be set dynamically by JavaScript -->
                        <form id="deleteProductForm" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Select all checkbox functionality
            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('tbody .form-check-input');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Handle edit product modal
            document.addEventListener('DOMContentLoaded', function() {
                const editProductModal = document.getElementById('editProductModal');
                if (editProductModal) {
                    editProductModal.addEventListener('show.bs.modal', async function(event) {
                        const button = event.relatedTarget; // Button that triggered the modal
                        const productId = button.getAttribute('data-product-id');

                        const form = document.getElementById('editProductForm');
                        // Construct URLs - Ensure these match your routes!
                        const updateUrl = `/admin/products/${productId}/update`;
                        const getDataUrl = `/admin/products/${productId}/edit-data`;

                        // Set the form action
                        form.action = updateUrl;

                        // Clear previous data / set loading state (optional)
                        form.reset(); // Simple way to clear
                        document.getElementById('currentProductImage').src =
                            'https://via.placeholder.com/100';


                        // Fetch product data
                        try {
                            const response = await fetch(getDataUrl);

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            const product = await response.json();

                            // Populate form fields
                            document.getElementById('editProductName').value = product.products_name || '';
                            document.getElementById('editProductCategory').value = product.categories_id ||'';
                            document.getElementById('editProductPrice').value = product.unit_price || '';
                            document.getElementById('editOrdersPrice').value = product.orders_price || '';
                            document.getElementById('editProductStock').value = product.products_stock ||'';
                            document.getElementById('editProductDescription').value = product.products_description || '';


                            // Set current image
                            if (product.products_image) {
                                document.getElementById('currentProductImage').src =
                                    `/storage/products/${product.products_image}`;
                            } else {
                                document.getElementById('currentProductImage').src =
                                    'https://via.placeholder.com/100';
                            }

                        } catch (error) {
                            console.error('Error fetching product data:', error);
                            alert('Could not load product data. Please try again.');
                        }
                    });
                }
            });

            // Handle delete product modal
            const deleteProductModal = document.getElementById('deleteProductModal');
            deleteProductModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-product-id');
                const productName = button.getAttribute('data-product-name');

                // Set the product name in the confirmation message
                document.getElementById('deleteProductName').textContent = productName;

                // Set the form action URL dynamically
                const form = document.getElementById('deleteProductForm');
                form.action = `/admin/products/delete/${productId}`;
            });

            // Search and filter functionality
            const searchInput = document.getElementById('searchInput');
            const categorySelect = document.getElementById('categorySelect');
            const statusSelect = document.getElementById('statusSelect');
            const resetBtn = document.getElementById('resetBtn');
            const filterBtn = document.getElementById('filterBtn');

            // Reset button
            resetBtn.addEventListener('click', function() {
                searchInput.value = '';
                categorySelect.selectedIndex = 0;
                statusSelect.selectedIndex = 0;
            });
        </script>
    @endsection
