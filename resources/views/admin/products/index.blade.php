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
        <div class="search-container mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0"
                            placeholder="Search products...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="categorySelect" class="form-select">
                        <option selected>All Categories</option>
                        @foreach ($categories as $id => $name)
                            <option value="{{ $id }}" {{ old('categories_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="statusSelect" class="form-select">
                        <option selected>All Status</option>
                        <option>In Stock</option>
                        <option>Low Stock</option>
                        <option>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-3">
                        <button id="filterBtn" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-filter me-1"></i>Filter Products
                        </button>
                        <button id="resetBtn" class="btn btn-outline-secondary">Reset</button>
                    </div>
                </div>
            </div>
        </div>

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
                                    action="{{ route('admin.products.delete', ['product' => $product->products_id]) }}" method="POST"
                                    style="display: inline;">
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
                        <form id="addProductForm" action="{{ route('admin.products.create') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="productName" class="form-label">Product Name*</label>
                                    <input type="text" class="form-control" id="productName" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="productCategory" class="form-label">Category*</label>
                                    <select class="form-select" id="productCategory" name="category" required>
                                        <option value="" selected disabled>Select category</option>
                                        @foreach ($categories as $category)
                                            <option>{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="productPrice" class="form-label">Price (Rp)*</label>
                                    <input type="number" class="form-control" id="productPrice" name="price"
                                        step="1" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="productStock" class="form-label">Stock Quantity*</label>
                                    <input type="number" class="form-control" id="productStock" name="stock"
                                        min="0" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="productDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="productDescription" name="description" rows="3"></textarea>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="productWeight" class="form-label">Weight/Size*</label>
                                    <input type="text" class="form-control" id="productWeight" name="weight"
                                        placeholder="e.g., 500g, 1kg" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="productID" class="form-label">ID*</label>
                                    <input type="text" class="form-control" id="productID" name="product_id"
                                        required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="productImage" class="form-label">Product Image*</label>
                                <input class="form-control" type="file" id="productImage" name="image" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Storage Temperature*</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="storage_temp"
                                            value="freezer" id="freezer" checked>
                                        <label class="form-check-label" for="freezer">
                                            Deep Freeze (-18°C or below)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="storage_temp"
                                            value="refrigerator" id="refrigerator">
                                        <label class="form-check-label" for="refrigerator">
                                            Refrigerator (0°C to 5°C)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="featuredProduct" name="featured">
                                <label class="form-check-label" for="featuredProduct">
                                    Featured Product (Tasty Picks)
                                </label>
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
                        <!-- Form action will be set dynamically by JavaScript -->
                        <form id="editProductForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editProductName" class="form-label">Product Name*</label>
                                    <input type="text" class="form-control" id="editProductName" name="name"
                                        value="" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editProductCategory" class="form-label">Category*</label>
                                    <select class="form-select" id="editProductCategory" name="category" required>
                                        @foreach ($categories as $category)
                                            <option>{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editProductPrice" class="form-label">Price (Rp)*</label>
                                    <input type="number" class="form-control" id="editProductPrice" name="price"
                                        step="1" min="0" value="" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editProductStock" class="form-label">Stock Quantity*</label>
                                    <input type="number" class="form-control" id="editProductStock" name="stock"
                                        min="0" value="" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="editProductDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editProductDescription" name="description" rows="3"></textarea>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editProductWeight" class="form-label">Weight/Size*</label>
                                    <input type="text" class="form-control" id="editProductWeight" name="weight"
                                        required value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="editProductID" class="form-label">ID*</label>
                                    <input type="text" class="form-control" id="editProductID" name="product_id"
                                        value="" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="editProductImage" class="form-label">Product Image*</label>
                                <div class="d-flex align-items-center mb-2">
                                    <img src="" id="currentProductImage" alt="Current product image"
                                        class="me-3 rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                    <span class="text-muted">Current image</span>
                                </div>
                                <input class="form-control" type="file" id="editProductImage" name="image">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Storage Temperature*</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="storage_temp"
                                            value="freezer" id="editFreezer">
                                        <label class="form-check-label" for="editFreezer">
                                            Deep Freeze (-18°C or below)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="storage_temp"
                                            value="refrigerator" id="editRefrigerator">
                                        <label class="form-check-label" for="editRefrigerator">
                                            Refrigerator (0°C to 5°C)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="editFeaturedProduct"
                                    name="featured">
                                <label class="form-check-label" for="editFeaturedProduct">
                                    Featured Product (Tasty Picks)
                                </label>
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
            const editProductModal = document.getElementById('editProductModal');
            editProductModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const productId = button.getAttribute('data-product-id');

                // Set the form action URL dynamically
                const form = document.getElementById('editProductForm');
                form.action = `/admin/products/update/${productId}`;

                // You would typically fetch product data via AJAX here
                // For now, using placeholder data
                document.getElementById('editProductName').value = 'Gourmet Frozen Pizza';
                document.getElementById('editProductCategory').value = 'Ready Meals';
                document.getElementById('editProductPrice').value = '25000';
                document.getElementById('editProductStock').value = '125';
                document.getElementById('editProductDescription').value =
                    'Delicious gourmet pizza with premium toppings, ready to bake from frozen.';
                document.getElementById('editProductWeight').value = '400g';
                document.getElementById('editProductID').value = productId;
                document.getElementById('currentProductImage').src =
                    '{{ asset('images/products-img/gourmet-pizza.jpg') }}';
                document.getElementById('editFreezer').checked = true;
                document.getElementById('editFeaturedProduct').checked = true;
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
