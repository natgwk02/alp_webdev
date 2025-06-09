@extends('layouts.admin')

@section('title', 'Dashboard - Chile Mart Admin')

@section('content')
    <div class="container my-4">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="fw-bold">Dashboard</h1>
                <p class="text-muted">Overview of your store performance</p>
            </div>
        </div>

        {{-- Statistics cards --}}
        <div class="row">
            <div class="col-lg-4 mb-4">
                <a href="{{ route('admin.orders') }}" class="text-decoration-none text-white">
                    <div class="card stat-card gradient-blue text-white rounded-4 h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1">Total Orders</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['total_orders'] ?? 0 }}</h2>
                            </div>
                            <div class="icon-circle">
                                <i class="fas fa-shopping-bag fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 mb-4">
                <a href="{{ route('admin.orders') }}" class="text-decoration-none text-white">
                    <div class="card stat-card gradient-green text-white rounded-4 h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1">Total Revenue</h6>
                                <h2 class="fw-bold mb-0">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}
                                </h2>
                            </div>
                            <div class="icon-circle">
                                <i class="fas fa-dollar-sign fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 mb-4">
                <a href="{{ route('admin.products') }}" class="text-decoration-none text-white">
                    <div class="card stat-card gradient-cyan text-white rounded-4 h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-semibold mb-1">Total Products</h6>
                                <h2 class="fw-bold mb-0">{{ $stats['total_products'] ?? 0 }}</h2>
                            </div>
                            <div class="icon-circle">
                                <i class="fas fa-box-open fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Recent Orders --}}
            <div class="col-lg-8 col-md-7 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Recent Orders</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentOrders as $order)
                                        <tr>
                                            <td>{{ $order->orders_id ?? 'N/A' }}</td>
                                            <td>{{ $order->orders_date->format('Y-m-d') }}</td>
                                            <td>{{ $order->user ? $order->user->users_name : 'N/A' }}</td>
                                            <td>Rp {{ number_format($order->orders_total_price ?? 0, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge {{ $order->status_badge_class ?? 'bg-secondary' }}">
                                                    {{ Str::title(str_replace('_', ' ', $order->orders_status ?? 'Unknown')) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No recent orders found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary mt-2">View All
                            Orders</a>
                    </div>
                </div>
            </div>

            {{-- Stock Alert Products --}}
            <div class="col-lg-4 col-md-5 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Stock Alert Products</h5>
                    </div>
                    <div class="card-body d-flex flex-column">
                        @if (isset($stockAlertProducts) && $stockAlertProducts->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach ($stockAlertProducts as $product)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="text-truncate" title="{{ $product->products_name }}">
                                            {{ Str::limit($product->products_name, 30) }}
                                        </span>
                                        @php
                                            $stock = $product->products_stock;
                                            $threshold = $product->low_stock_threshold ?? 10;
                                            $badgeClass = '';
                                            if ($stock <= 0) {
                                                $badgeClass = 'bg-dark text-white';
                                            } elseif ($stock < 5) {
                                                $badgeClass = 'bg-danger';
                                            } elseif ($stock <= $threshold) {
                                                $badgeClass = 'bg-warning text-dark';
                                            } else {
                                                $badgeClass = 'bg-light text-dark';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} rounded-pill">
                                            {{ $stock }} left
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-center text-muted mb-0">No low stock products.</p>
                        @endif
                        <a href="{{ route('admin.products', ['status' => 'Low Stock']) }}"
                            class="btn btn-sm btn-outline-primary mt-auto">Manage Products
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Order Status Overview --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Order Status Overview</h5>
                    </div>
                    <div class="card-body p-0">
                        @if (isset($orderStatusOverview) && $orderStatusOverview->count() > 0)
                            <ul class="list-group list-group-flush">
                                @foreach ($orderStatusOverview as $statusData)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                                        <a href="{{ route('admin.orders', ['status' => $statusData->name]) }}"
                                            class="text-decoration-none link-dark text-truncate"
                                            title="{{ Str::title(str_replace('_', ' ', $statusData->name)) }}">
                                            {{ Str::title(str_replace('_', ' ', $statusData->name)) }}
                                            <i class="fas fa-link fa-xs ms-1"></i>
                                        </a>
                                        <span class="badge {{ $statusData->badge_class }} rounded-pill ms-2">
                                            {{ $statusData->count }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center p-3">
                                <p class="text-muted mb-0">No order status data available.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Top Products by Revenue (Pie Chart) --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Top {{ $topLimit ?? 5 }} Products by Revenue</h5>
                        @if (isset($periodStartDate) && isset($periodEndDate))
                            <small class="text-muted">({{ $periodStartDate->format('M d') }} -
                                {{ $periodEndDate->format('M d, Y') }})</small>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        @if (isset($topProductsByRevenue) && $topProductsByRevenue->count() > 0)
                            <div style="position: relative; width: 100%; flex-grow: 1;">
                                <canvas id="topRevenueChart"></canvas>
                            </div>
                        @else
                            <p class="text-center text-muted my-auto">No sales data available.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Top Products by Quantity (Pie Chart) --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Top {{ $topLimit ?? 5 }} Products by Quantity</h5>
                        @if (isset($periodStartDate) && isset($periodEndDate))
                            <small class="text-muted">({{ $periodStartDate->format('M d') }} -
                                {{ $periodEndDate->format('M d, Y') }})</small>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        @if (isset($topProductsByQuantity) && $topProductsByQuantity->count() > 0)
                            <div style="position: relative; width: 100%; flex-grow: 1;">
                                <canvas id="topQuantityChart"></canvas>
                            </div>
                        @else
                            <p class="text-center text-muted my-auto">No sales data available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales Trends Chart Row --}}
        <div class="row mt-2 mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div
                        class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <h5 class="mb-2 mb-md-0 me-md-3">Sales Trends</h5>
                        <div class="d-flex flex-wrap justify-content-md-end">
                            <div class="me-2 mb-2 mb-md-0">
                                <select id="salesTrendPeriod" class="form-select form-select-sm"
                                    aria-label="Select Period">
                                    <option value="7d">Last 7 Days</option>
                                    <option value="30d" selected>Last 30 Days</option>
                                    <option value="monthly_6">Last 6 Months</option>
                                    <option value="monthly_12">Last 12 Months</option>
                                </select>
                            </div>
                            <div>
                                <select id="salesTrendDataType" class="form-select form-select-sm"
                                    aria-label="Select Data Type">
                                    <option value="revenue" selected>Revenue</option>
                                    <option value="orders">Orders</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height:300px; width:100%;">
                            <canvas id="salesTrendChart"></canvas>
                            <div id="salesTrendChartSpinner" class="text-center"
                                style="display: none; position: absolute; top: 45%; left: 50%; transform: translate(-50%, -50%); z-index:10;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div id="salesTrendChartNoData" class="text-center text-muted my-auto"
                                style="display: none; position: absolute; top: 45%; left: 50%; transform: translate(-50%, -50%);">
                                No sales trend data available for this period.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('styles')
        <style>
            .gradient-blue {
                background: linear-gradient(135deg, #1E90FF, #4682B4);
            }

            .gradient-green {
                background: linear-gradient(135deg, #28a745, #218838);
            }

            .gradient-cyan {
                background: linear-gradient(135deg, #17a2b8, #138496);
            }

            .icon-circle {
                background-color: rgba(255, 255, 255, 0.15);
                border-radius: 50%;
                padding: 15px;
                box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
                transition: transform 0.3s ease;
            }

            .stat-card:hover .icon-circle {
                transform: scale(1.1);
            }

            .card .list-group-item {
                border-left: none;
                border-right: none;
            }

            .card .list-group-item:first-child {
                border-top: none;
                border-top-left-radius: 0;
                border-top-right-radius: 0;
            }

            .card .list-group-item:last-child {
                border-bottom: none;
                border-bottom-left-radius: 0;
                border-bottom-right-radius: 0;
            }

            .card-body.p-0 .list-group-flush:first-child .list-group-item:first-child {
                border-top-left-radius: 0rem !important;
                border-top-right-radius: 0rem !important;
            }

            .card-body.p-0 .list-group-flush:last-child .list-group-item:last-child {
                border-bottom-left-radius: 0rem !important;
                border-bottom-right-radius: 0rem !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {


                const chartColors = ['#28a745', '#007bff', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#fd7e14',
                    '#20c997', '#6610f2', '#e83e8c'
                ];

                @if (isset($topProductsByRevenue) && $topProductsByRevenue->count() > 0)
                    const topRevenueCtx = document.getElementById('topRevenueChart')?.getContext('2d');
                    if (topRevenueCtx) {
                        const topRevenueLabels = [
                            @foreach ($topProductsByRevenue as $product)
                                '{{ Str::limit(addslashes($product->products_name), 20) }}',
                            @endforeach
                        ];
                        const topRevenueValues = [
                            @foreach ($topProductsByRevenue as $product)
                                {{ $product->total_revenue }},
                            @endforeach
                        ];
                        new Chart(topRevenueCtx, {

                            type: 'pie',
                            data: {
                                labels: topRevenueLabels,
                                datasets: [{
                                    label: 'Revenue',
                                    data: topRevenueValues,
                                    backgroundColor: chartColors.slice(0, topRevenueValues.length),
                                    hoverOffset: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 10,
                                            padding: 10,
                                            font: {
                                                size: 11
                                            }
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let valueLabel = '';
                                                if (context.parsed !== null) {
                                                    valueLabel = new Intl.NumberFormat('id-ID', {
                                                        style: 'currency',
                                                        currency: 'IDR',
                                                        minimumFractionDigits: 0
                                                    }).format(context.parsed);
                                                }
                                                const originalProductNames = @json($topProductsByRevenue->pluck('products_name'));
                                                const fullProductName = originalProductNames[context
                                                    .dataIndex] || context.label || '';
                                                return fullProductName + ': ' + valueLabel;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                @endif

                @if (isset($topProductsByQuantity) && $topProductsByQuantity->count() > 0)
                    const topQuantityCtx = document.getElementById('topQuantityChart')?.getContext('2d');
                    if (topQuantityCtx) {
                        const topQuantityLabels = [
                            @foreach ($topProductsByQuantity as $product)
                                '{{ Str::limit(addslashes($product->products_name), 20) }}',
                            @endforeach
                        ];
                        const topQuantityValues = [
                            @foreach ($topProductsByQuantity as $product)
                                {{ $product->total_quantity }},
                            @endforeach
                        ];
                        new Chart(topQuantityCtx, {
                            type: 'pie',
                            data: {
                                labels: topQuantityLabels,
                                datasets: [{
                                    label: 'Quantity Sold',
                                    data: topQuantityValues,
                                    backgroundColor: chartColors.reverse().slice(0, topQuantityValues
                                        .length),
                                    hoverOffset: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 10,
                                            padding: 10,
                                            font: {
                                                size: 11
                                            }
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let valueLabel = '';
                                                if (context.parsed !== null) {
                                                    valueLabel = context.parsed + ' units';
                                                }
                                                const originalProductNames = @json($topProductsByQuantity->pluck('products_name'));
                                                const fullProductName = originalProductNames[context
                                                    .dataIndex] || context.label || '';
                                                return fullProductName + ': ' + valueLabel;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }
                @endif

                // Sales Trend Chart
                let salesTrendChartInstance;
                const salesTrendCtx = document.getElementById('salesTrendChart')?.getContext('2d');
                const salesTrendSpinner = document.getElementById('salesTrendChartSpinner');
                const salesTrendNoData = document.getElementById('salesTrendChartNoData');

                const periodSelect = document.getElementById('salesTrendPeriod');
                const dataTypeSelect = document.getElementById('salesTrendDataType');

                function formatYAxisTick(value, dataType) {
                    if (dataType === 'revenue') {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            notation: 'compact',
                            compactDisplay: 'short'
                        }).format(value);
                    }
                    return Number.isInteger(value) ? value : value.toFixed(1); // Show integer for counts
                }

                function formatTooltipLabel(context, currentDataType) {
                    let label = context.dataset.label || '';
                    if (label) {
                        label += ': ';
                    }
                    if (context.parsed.y !== null) {
                        if (currentDataType === 'revenue') {
                            label += new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0
                            }).format(context.parsed.y);
                        } else {
                            label += context.parsed.y + (context.parsed.y === 1 ? ' order' : ' orders');
                        }
                    }
                    return label;
                }

                function updateSalesTrendChart(period, dataType) {
                    if (!salesTrendCtx) return;
                    if (salesTrendSpinner) salesTrendSpinner.style.display = 'block';
                    if (salesTrendNoData) salesTrendNoData.style.display = 'none';
                    if (salesTrendChartInstance) salesTrendChartInstance
                        .destroy();
                    const url = `{{ route('admin.salesTrendData') }}?period=${period}&dataType=${dataType}`;

                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response error.');
                            }
                            return response.json();
                        })
                        .then(responseData => {
                            if (responseData.labels && responseData.labels.length > 0 && responseData.datasets &&
                                responseData.datasets[0].data.length > 0) {
                                if (salesTrendNoData) salesTrendNoData.style.display = 'none';
                                salesTrendChartInstance = new Chart(salesTrendCtx, {
                                    type: 'line',
                                    data: {
                                        labels: responseData.labels,
                                        datasets: responseData.datasets.map(dataset => ({
                                            ...dataset, / // Start with all properties from the original 'dataset'
                                            pointBackgroundColor: dataset.borderColor ||
                                                'rgb(54, 162, 235)',
                                            pointBorderColor: '#fff',
                                            pointHoverBackgroundColor: '#fff',
                                            pointHoverBorderColor: dataset.borderColor ||
                                                'rgb(54, 162, 235)'
                                        }))
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: (value) => formatYAxisTick(value, responseData
                                                        .dataType)
                                                }
                                            },
                                            x: {
                                                ticks: {
                                                    maxRotation: 0,
                                                    autoSkip: true,
                                                    maxTicksLimit: responseData.period.startsWith(
                                                        'monthly') ? (responseData.labels.length > 12 ?
                                                        12 :
                                                        responseData.labels.length) : (responseData
                                                        .labels.length > 15 ? 15 : responseData.labels
                                                        .length)
                                                }
                                            }
                                        },
                                        plugins: {
                                            legend: {
                                                display: responseData.datasets.length > 0 && responseData
                                                    .datasets[0].label,
                                                position: 'top'
                                            },
                                            tooltip: {
                                                mode: 'index',
                                                intersect: false,
                                                callbacks: {
                                                    label: (context) => formatTooltipLabel(context,
                                                        responseData.dataType)
                                                }
                                            }
                                        },
                                        interaction: {
                                            mode: 'index',
                                            intersect: false
                                        }
                                    }
                                });
                            } else {
                                if (salesTrendNoData) salesTrendNoData.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching or rendering sales trend data:', error);
                            if (salesTrendNoData) {
                                salesTrendNoData.textContent = 'Error loading chart data.';
                                salesTrendNoData.style.display = 'block';
                            }
                        })
                        .finally(() => {
                            if (salesTrendSpinner) salesTrendSpinner.style.display = 'none';
                        });
                }

                if (periodSelect && dataTypeSelect) {
                    periodSelect.addEventListener('change', function() {
                        updateSalesTrendChart(this.value, dataTypeSelect.value);
                    });
                    dataTypeSelect.addEventListener('change', function() {
                        updateSalesTrendChart(periodSelect.value, this.value);
                    });

                    updateSalesTrendChart(periodSelect.value, dataTypeSelect.value);
                }
            });
        </script>
    @endpush
@endsection
