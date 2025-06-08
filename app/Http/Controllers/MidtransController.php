<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $signatureKey = $request->header('X-Signature-Key') ?? $request->input('signature_key');
        $rawBody = $request->getContent();
        $json = json_decode($rawBody);

        // Validasi signature (optional tapi penting)
        $expectedSignature = hash("sha512", $rawBody . $serverKey);
        if ($signatureKey !== $expectedSignature) {
            Log::warning('Invalid signature callback from Midtrans.');
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $json->order_id; // Ini harus sama dengan invoice_number
        $transactionStatus = $json->transaction_status;
        $paymentType = $json->payment_type;
        $fraudStatus = $json->fraud_status ?? null;

        $order = Order::where('invoice_number', $orderId)->first();

        if (!$order) {
            Log::error("Order not found: " . $orderId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status sesuai response
        if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
            $order->payment_status = 'paid';
        } elseif (in_array($transactionStatus, ['settlement'])) {
            $order->payment_status = 'paid';
        } elseif (in_array($transactionStatus, ['pending'])) {
            $order->payment_status = 'pending';
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            $order->payment_status = 'failed';
        }

        $order->save();

        return response()->json(['message' => 'Payment status updated'], 200);
    }
}
