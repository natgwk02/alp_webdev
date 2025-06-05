<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
public function checkStatus(Order $order)
{
    $status_message = $this->getStatusMessage($order->orders_status);

    return view('payment.status', compact('order', 'status_message'));
}



    public function updateStatus(Request $request, Order $order)
    {
        // Logika untuk memperbarui status pembayaran
        // Misalnya dari callback payment gateway
        
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