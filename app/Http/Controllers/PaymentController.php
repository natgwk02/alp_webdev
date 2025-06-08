<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Transaction;
use Midtrans\Config;


class PaymentController extends Controller
{

public function checkStatus(Order $order)
{
    // Jika belum paid, coba ambil status real-time dari Midtrans
    if ($order->payment_status !== 'paid') {
        try {
$midtransOrderId = $order->invoice_number;
/** @var object $status */
$status = \Midtrans\Transaction::status($midtransOrderId);

            if ($status->transaction_status === 'settlement' || $status->transaction_status === 'capture') {
                $order->payment_status = 'paid';
                $order->orders_status = 'completed';
                $order->save();
            } elseif ($status->transaction_status === 'pending') {
                $order->payment_status = 'pending';
                $order->save();
            } elseif (in_array($status->transaction_status, ['cancel', 'expire', 'deny'])) {
                $order->payment_status = 'failed';
                $order->save();
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengambil status pembayaran: ' . $e->getMessage());
        }
    }

    $status_message = $this->getStatusMessage($order->orders_status);
    return view('payment.status', compact('order', 'status_message'));
}




    public function updateStatus(Request $request, Order $order)
    {
        $order->update(['status' => $request->status]);
        
        return redirect()->route('payment.status', $order)
            ->with('success', 'Payment status updated successfully');
    }

    protected function getStatusMessage($status)
    {
        $messages = [
            'pending' => 'Your payment is being processed. Please complete your payment.',
            'completed' => 'Payment has been successfully processed.',
            'failed' => 'Payment failed. Please try again.',
            'expired' => 'Payment period has expired. Please create a new order.',
        ];

        return $messages[$status] ?? 'Current payment status: ' . $status;
    }
}