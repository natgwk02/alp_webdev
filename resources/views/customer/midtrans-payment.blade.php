@extends('layouts.app')

@section('content')
    <div class="container text-center py-5">
        <h3>Redirecting to Midtrans Payment...</h3>
        <p>Please wait while the Snap popup loads.</p>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const snapToken = "{{ $snapToken }}";
            const orderId = "{{ $orderId }}";
            const paymentStatusUrl = "{{ $paymentStatusUrl }}";
            const cartUrl = "{{ route('cart.index') }}";

            if (!snapToken || snapToken === '') {
                alert("Invalid Snap Token. Please try again.");
                window.location.href = cartUrl;
                return;
            }

            snap.pay(snapToken, {
                onSuccess: function(result) {
                    console.log("Payment Success:", result);
                    window.location.href = paymentStatusUrl;
                },
                onPending: function(result) {
                    console.log("Payment Pending:", result);
                    window.location.href = paymentStatusUrl;
                },
                onError: function(result) {
                    console.error("Payment Error:", result);
                    alert("Payment failed. You will be redirected.");
                    window.location.href = cartUrl;
                },
                onClose: function() {
                    alert("You closed the payment popup.");
                    window.location.href = cartUrl;
                }
            });
        });
    </script>
@endsection
