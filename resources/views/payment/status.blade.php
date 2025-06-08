@extends('base.base')

@section('content')
    <div class="container">
        <h1>Payment Status</h1>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-5">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Invoice Number:</strong> {{ $order->invoice_number }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Total Amount:</strong> Rp {{ number_format($order->orders_total_price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Payment Status:</strong>
                            <span
                                class="badge 
        @if ($order->payment_status == 'paid') bg-success
        @elseif($order->payment_status == 'pending') bg-warning
        @elseif($order->payment_status == 'failed') bg-danger
        @else bg-secondary @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>

                    </div>
                </div>

                @if (!empty($status_message))
                    <div
                        class="alert 
                    @if ($order->payment_status == 'paid') bg-success
                    @elseif($order->payment_status == 'pending') bg-warning
                    @elseif($order->payment_status == 'failed') bg-danger
                    @else bg-secondary @endif">

                        {{ $status_message }}
                    </div>
                @endif

                <div class="d-flex
                        justify-content-between mt-4">
                    <a href="{{ route('payment.status', ['order' => $order]) }}" class="btn btn-secondary">
                        <i class="fas fa-sync-alt"></i> Check Status Again
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">
                        <i class="fas fa-list"></i> View My Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
