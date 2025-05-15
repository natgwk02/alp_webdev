@extends('layouts.app')

@section('title', 'My Orders - Chile Mart')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-4 text-center text-primary">My Orders</h1>

    @if(count($orders) > 0)
        <div class="d-flex flex-column gap-4">
            @foreach($orders as $order)
                <div class="border rounded shadow-sm p-4 bg-white">
                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold text-secondary">Order #{{ $order['order_number'] }}</span>
                        @php
                            $statusClass = match($order['status']) {
                                'Delivered' => 'badge bg-success',
                                'Processing' => 'badge bg-warning text-dark',
                                'Cancelled' => 'badge bg-danger',
                                default => 'badge bg-secondary'
                            };
                        @endphp
                        <span class="{{ $statusClass }}">{{ $order['status'] }}</span>
                    </div>

                    {{-- Tanggal & item count --}}
                    <div class="mb-3 text-muted small">
                        <i class="fas fa-calendar-alt me-1"></i>
                        {{ \Carbon\Carbon::parse($order['order_date'])->format('d M Y') }}
                        &nbsp; | &nbsp;
                        <i class="fas fa-boxes me-1"></i>
                        {{ $order['item_count'] }} item{{ $order['item_count'] > 1 ? 's' : '' }}
                    </div>

                    {{-- Gambar + Nama Produk --}}
                    @php
                        $firstItem = $order['items'][0] ?? null;
                        $firstImage = $firstItem['image'] ?? 'no-image.png';
                        $firstName = $firstItem['product_name'] ?? 'Unknown Product';
                    @endphp
                    <div class="d-flex gap-4 align-items-center mb-4">
                        <img src="{{ asset('images/products-img/' . $firstImage) }}"
                             alt="{{ $firstName }}"
                             class="rounded border"
                             style="width: 100px; height: 100px; object-fit: cover;">

                        <div class="flex-grow-1">
                            <div class="fw-semibold fs-6">{{ \Illuminate\Support\Str::limit($firstName, 50) }}</div>
                        </div>
                    </div>

                    {{-- Total + Actions --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="fw-bold fs-5 text-dark">
                            Total: Rp {{ number_format($order['total_amount'], 0, ',', '.') }}
                        </div>
                        <div class="mt-2 mt-md-0 d-flex gap-2">
                            <a href="{{ route('order.detail', ['id' => $order['id']]) }}"
                               class="btn btn-outline-primary btn-sm px-4">
                                View Details
                            </a>
                            @if($order['status'] === 'Delivered')
                                <form action="{{ route('order.received', ['id' => $order['id']]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm px-4">
                                        Order Received
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card-body">
            @if(count($orders) > 0)
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Items</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order['order_number'] }}</td>
                            <td>{{ $order['order_date'] }}</td>
                            <td>
                                <span class="badge 
                                    @if($order['status'] == 'Delivered') bg-success 
                                    @elseif($order['status'] == 'Processing') bg-warning text-dark 
                                    @else bg-secondary 
                                    @endif">
                                    {{ $order['status'] }}
                                </span>
                            </td>
                            <td>${{ number_format($order['total_amount'], 2) }}</td>
                            <td>{{ $order['item_count'] }}</td>
                            <td>
                                <a href="{{ route('order.details', ['id' => $order['id']]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                You have no orders yet.
            </div>
            @endif
        </div>
    @endif
</div>
@endsection
