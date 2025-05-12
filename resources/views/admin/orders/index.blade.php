@extends('layouts.admin')

@section('title', 'Order Management - Chile Mart Admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold">Order Management</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Orders</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order['order_number'] }}</td>
                                    <td>{{ $order['customer_name'] }}</td>
                                    <td>{{ $order['order_date'] }}</td>
                                    <td>${{ number_format($order['total_amount'], 2) }}</td>
                                    <td>{{ $order['payment_method'] }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($order['status'] == 'Processing') bg-warning
                                            @elseif($order['status'] == 'Shipped') bg-info
                                            @elseif($order['status'] == 'Delivered') bg-success
                                            @else bg-secondary
                                            @endif">
                                            {{ $order['status'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order['id']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection