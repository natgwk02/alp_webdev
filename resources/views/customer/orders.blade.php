@extends('layouts.app')

@section('title', 'My Orders - Chile Mart')

@section('content')
<div class="container py-4">
    <h1 class="fw-bold mb-4">My Orders</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Order History</h5>
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
                                <a href="{{ route('order.detail', ['id' => $order['id']]) }}" class="btn btn-sm btn-outline-primary">
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
    </div>
</div>
@endsection
