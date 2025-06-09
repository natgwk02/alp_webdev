@extends('layouts.app')

@section('title', 'Order Confirmation - Chile Mart')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">Order Confirmation</h3>
                    </div>
                    <div class="card-body text-center">
                        <i class="bi bi-check-circle-fill text-success display-4 mb-4"></i>
                        <h4 class="mb-3">Terima kasih atas pesanan Anda!</h4>
                        <p class="lead">Nomor Order: <strong>{{ $order['id'] }}</strong></p>

                        <div class="alert alert-info text-start">
                            <h5>Detail Pesanan:</h5>
                            <p>Total Pembayaran: <strong>Rp{{ number_format($order['total'], 0, ',', '.') }}</strong></p>
                            <p>Metode Pembayaran: <strong>{{ ucfirst($order['payment_method']) }}</strong></p>
                            <p>Status: <span class="badge bg-warning text-dark">{{ ucfirst($order['status']) }}</span></p>
                        </div>

                        <p>Kami telah mengirimkan detail pesanan ke email
                            <strong>{{ $order['customer']['email'] }}</strong>.
                        </p>

                        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
