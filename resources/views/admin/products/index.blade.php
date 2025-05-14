@extends('layouts.app') {{-- ganti ke layouts.admin kali uda ada --}}

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
            width: 80px;
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

    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Products Management</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-lg"></i> Add New Product
            </button>
        </div>

        {{-- search n filter --}}
        <div class="search-container mb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" placeholder="Search products...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option selected>All Categories</option>
                        <option>Ready Meals</option>
                        <option>Frozen Vegetables</option>
                        <option>Dimsum</option>
                        <option>Frozen Meat & Fish</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select">
                        <option selected>All Status</option>
                        <option>In Stock</option>
                        <option>Low Stock</option>
                        <option>Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary flex-grow-1">Filter</button>
                        <button class="btn btn-outline-secondary">Reset</button>
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
                    <!-- Product 1 -->
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/60" alt="Gourmet Frozen Pizza"
                                    class="product-img me-3">
                                <div>
                                    <h6 class="mb-0">Gourmet Frozen Pizza</h6>
                                    <small class="text-muted">#PRD001</small>
                                </div>
                            </div>
                        </td>
                        <td>Ready Meals</td>
                        <td>Rp 25.000</td>
                        <td>125</td>
                        <td><span class="badge bg-success status-badge">In Stock</span></td>
                        <td>May 10, 2025</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal"
                                data-bs-target="#editProductModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger action-btn" data-bs-toggle="modal"
                                data-bs-target="#deleteProductModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Product 2 -->
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/60" alt="Organic Mixed Vegetables"
                                    class="product-img me-3">
                                <div>
                                    <h6 class="mb-0">Organic Mixed Vegetables</h6>
                                    <small class="text-muted">#PRD002</small>
                                </div>
                            </div>
                        </td>
                        <td>Frozen Vegetables</td>
                        <td>Rp 36.000</td>
                        <td>210</td>
                        <td><span class="badge bg-success status-badge">In Stock</span></td>
                        <td>May 12, 2025</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal"
                                data-bs-target="#editProductModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger action-btn" data-bs-toggle="modal"
                                data-bs-target="#deleteProductModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Product 3 -->
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/60" alt="Premium Vanilla Ice Cream"
                                    class="product-img me-3">
                                <div>
                                    <h6 class="mb-0">Premium Vanilla Ice Cream</h6>
                                    <small class="text-muted">#PRD003</small>
                                </div>
                            </div>
                        </td>
                        <td>Ice Cream & Desserts</td>
                        <td>Rp 43.000</td>
                        <td>78</td>
                        <td><span class="badge bg-success status-badge">In Stock</span></td>
                        <td>May 11, 2025</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal"
                                data-bs-target="#editProductModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger action-btn" data-bs-toggle="modal"
                                data-bs-target="#deleteProductModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Product 4 -->
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/60" alt="Chicken Alfredo Meal"
                                    class="product-img me-3">
                                <div>
                                    <h6 class="mb-0">Chicken Alfredo Meal</h6>
                                    <small class="text-muted">#PRD004</small>
                                </div>
                            </div>
                        </td>
                        <td>Ready Meals</td>
                        <td>Rp 39.000</td>
                        <td>15</td>
                        <td><span class="badge bg-warning text-dark status-badge">Low Stock</span></td>
                        <td>May 9, 2025</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal"
                                data-bs-target="#editProductModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger action-btn" data-bs-toggle="modal"
                                data-bs-target="#deleteProductModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Product 5 -->
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/60" alt="Frozen Salmon Fillets"
                                    class="product-img me-3">
                                <div>
                                    <h6 class="mb-0">Frozen Salmon Fillets</h6>
                                    <small class="text-muted">#PRD005</small>
                                </div>
                            </div>
                        </td>
                        <td>Frozen Meat & Fish</td>
                        <td>Rp 74.999</td>
                        <td>0</td>
                        <td><span class="badge bg-danger status-badge">Out of Stock</span></td>
                        <td>May 8, 2025</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal"
                                data-bs-target="#editProductModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger action-btn" data-bs-toggle="modal"
                                data-bs-target="#deleteProductModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Product 6 -->
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://via.placeholder.com/60" alt="Chocolate Chip Cookie Dough"
                                    class="product-img me-3">
                                <div>
                                    <h6 class="mb-0">Chocolate Chip Cookie Dough</h6>
                                    <small class="text-muted">#PRD006</small>
                                </div>
                            </div>
                        </td>
                        <td>Ice Cream & Desserts</td>
                        <td>Rp 24.999</td>
                        <td>89</td>
                        <td><span class="badge bg-success status-badge">In Stock</span></td>
                        <td>May 7, 2025</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal"
                                data-bs-target="#editProductModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger action-btn" data-bs-toggle="modal"
                                data-bs-target="#deleteProductModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Page -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <p class="mb-0">Showing 1 to 6 of 24 entries</p>
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    </div>

    {{-- modal --}}

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
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="productName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="productName" required>
                            </div>
                            <div class="col-md-6">
                                <label for="productCategory" class="form-label">Category</label>
                                <select class="form-select" id="productCategory" required>
                                    <option value="" selected disabled>Select category</option>
                                    <option>Ready Meals</option>
                                    <option>Frozen Vegetables</option>
                                    <option>Ice Cream & Desserts</option>
                                    <option>Frozen Meat & Fish</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="productPrice" class="form-label">Price (Rp)</label>
                                <input type="number" class="form-control" id="productPrice" step="0.01"
                                    min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="productStock" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" id="productStock" min="0" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="productDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="productDescription" rows="3" required></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="productWeight" class="form-label">Weight/Size</label>
                                <input type="text" class="form-control" id="productWeight"
                                    placeholder="e.g., 500g, 1kg">
                            </div>
                            <div class="col-md-6">
                                <label for="productID" class="form-label">ID</label>
                                <input type="text" class="form-control" id="productID">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="productImage" class="form-label">Product Image</label>
                            <input class="form-control" type="file" id="productImage">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Storage Temperature</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="storageTemp" id="freezer"
                                        checked>
                                    <label class="form-check-label" for="freezer">
                                        Deep Freeze (-18°C or below)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="storageTemp" id="refrigerator">
                                    <label class="form-check-label" for="refrigerator">
                                        Refrigerator (0°C to 5°C)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="featuredProduct">
                            <label class="form-check-label" for="featuredProduct">
                                Featured Product (Tasty Picks)
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Product</button>
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
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editProductName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="editProductName"
                                    value="Gourmet Frozen Pizza" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editProductCategory" class="form-label">Category</label>
                                <select class="form-select" id="editProductCategory" required>
                                    <option>Ready Meals</option>
                                    <option>Frozen Vegetables</option>
                                    <option>Ice Cream & Desserts</option>
                                    <option>Frozen Meat & Fish</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editProductPrice" class="form-label">Price (Rp)</label>
                                <input type="number" class="form-control" id="editProductPrice" step="0.01"
                                    min="0" value="8.99" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editProductStock" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control" id="editProductStock" min="0"
                                    value="125" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editProductDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editProductDescription" rows="3" required>Delicious gourmet pizza with premium toppings, ready to bake from frozen.</textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="editProductWeight" class="form-label">Weight/Size</label>
                                <input type="text" class="form-control" id="editProductWeight" value="400g">
                            </div>
                            <div class="col-md-6">
                                <label for="editProductID" class="form-label">ID</label>
                                <input type="text" class="form-control" id="editProductID" value="PRD001">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="editProductImage" class="form-label">Product Image</label>
                            <div class="d-flex align-items-center mb-2">
                                <img src="https://via.placeholder.com/100" alt="Current product image"
                                    class="me-3 rounded">
                                <span class="text-muted">Current image</span>
                            </div>
                            <input class="form-control" type="file" id="editProductImage">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Storage Temperature</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="editStorageTemp"
                                        id="editFreezer" checked>
                                    <label class="form-check-label" for="editFreezer">
                                        Deep Freeze (-18°C or below)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="editStorageTemp"
                                        id="editRefrigerator">
                                    <label class="form-check-label" for="editRefrigerator">
                                        Refrigerator (0°C to 5°C)
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="editFeaturedProduct" checked>
                            <label class="form-check-label" for="editFeaturedProduct">
                                Featured Product
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
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
                    <p>Are you sure you want to delete <strong>Gourmet Frozen Pizza</strong>?</p>
                    <p class="text-danger mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Delete Product</button>
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
    </script>

@endsection
