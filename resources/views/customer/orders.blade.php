@extends('layouts.app')

@section('title', 'My Orders - Chile Mart')

@section('content')
    <div class="container py-5">
        <h1 class="fw-bold mb-4 text-center" style="color: #052659;">My Orders</h1>

        @if (count($orders) > 0)
            <div class="d-flex flex-column gap-4">
                @foreach ($orders as $order)
                    <div class="border rounded shadow-sm p-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-secondary">Order ID:
                                {{ $order['orders_id'] }}</span>
                            @php
                                $statusClass = match ($order['orders_status']) {
                                    'Delivered' => 'badge bg-success',
                                    'Processing' => 'badge bg-warning text-white',
                                    'Cancelled' => 'badge bg-danger',
                                    'Shipped' => 'badge bg-info',
                                    'Pending' => 'badge bg-secondary',
                                    'Completed' => 'badge bg-success',
                                    default => 'badge bg-secondary',
                                };
                            @endphp
                            <span class="{{ $statusClass }}">{{ $order['orders_status'] }}</span>
                        </div>

                        <div class="mb-3 text-muted small">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->format('d M Y') : 'Tanggal tidak tersedia' }}
                            &nbsp; | &nbsp;
                            <i class="fas fa-boxes me-1"></i>
                            {{ count($order['items']) }} item{{ count($order['items']) > 1 ? 's' : '' }}
                        </div>

                        @foreach ($order['items'] as $item)
                            <div class="d-flex gap-4 align-items-center mb-3">
                                <img src="{{ asset('images/products-img/' . ($item['product_image'] ?? 'no-image.png')) }}"
                                    alt="{{ $item['product_name'] ?? 'Unknown Product' }}" class="rounded border"
                                    style="width: 80px; height: 80px; object-fit: cover;">

                                <div class="flex-grow-1">
                                    <div class="fw-semibold fs-6">
                                        {{ \Illuminate\Support\Str::limit($item['product_name'] ?? 'Unknown Product', 50) }}
                                    </div>

                                    <div class="text-muted">Qty: {{ $item['quantity'] ?? 'N/A' }}</div>
                                    <div class="text-muted">Price: Rp{{ number_format($item['price'] ?? 0, 0, ',', '.') }}</div>

                                    @if ($order['orders_status'] === 'Delivered')
                                        @php
                                            $alreadyRated = \App\Models\Rating::where('user_id', Auth::id())
                                                ->where('product_id', $item['product_id'])
                                                ->exists();
                                        @endphp

                                        @if (!$alreadyRated)
                                            <form action="{{ route('ratings.store') }}" method="POST" class="ms-auto d-flex align-items-center mt-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                                <span class="me-2 small text-muted">Beri Rating:</span>
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <button type="submit" name="rating" value="{{ $i }}" class="btn btn-link p-0 border-0">
                                                        <i class="bi bi-star text-warning fs-5"></i>
                                                    </button>
                                                @endfor
                                            </form>
                                        @else
                                            <div class="text-success small mt-2">You already rated this product.</div>
                                        @endif
                                    @endif

                                </div>

                                <div class="fw-bold text-dark">
                                    Total: Rp{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">
                            <div class="fw-bold fs-5 text-dark">
                                Order Total: Rp
                                {{ isset($order['total']) ? number_format($order['total'], 0, ',', '.') : '0' }}
                            </div>
                            <div class="mt-2 mt-md-0 d-flex gap-2">
                                <a href="{{ route('order.detail', ['id' => $order['orders_id']]) }}"
                                    class="btn btn-outline-primary btn-sm px-4">
                                    View Details
                                </a>
                                @if (isset($order['status']) && $order['status'] === 'Delivered')
                                    <form action="{{ route('order.received', ['id' => $order['id']]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm px-4">
                                            Order Received
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                You have no orders yet.
            </div>
        @endif
    </div>
@endsection
