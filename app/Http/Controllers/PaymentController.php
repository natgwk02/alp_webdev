<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function handleReturn(Order $order)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            /** @var object $status */
            $status = Transaction::status($order->invoice_number);

            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $order->payment_status = 'paid';
            } elseif ($status->transaction_status == 'pending') {
                $order->payment_status = 'pending';
            } elseif ($status->transaction_status == 'expire') {
                $order->payment_status = 'expired';
            } elseif ($status->transaction_status == 'cancel') {
                $order->payment_status = 'cancelled';
            } else {
                $order->payment_status = $status->transaction_status;
            }
            $order->payment_method = $status->payment_type ?? 'unknown';

            $order->save();

            return redirect()->route('order.detail', ['id' => $order->orders_id])
                ->with('success', 'Payment status updated.');
        } catch (\Exception $e) {
            return redirect()->route('payment.status', $order->id)
                ->with('error', 'Auto-check failed: ' . $e->getMessage());
        }
    }

    public function checkStatus(Order $order, Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $status = \Midtrans\Transaction::status($order->invoice_number);

            $statusMap = [
                'settlement' => 'paid',
                'capture'    => 'paid',
                'pending'    => 'pending',
                'expire'     => 'expired',
                'cancel'     => 'cancelled',
                'deny'       => 'failed',
            ];

            $transactionStatus = $status->transaction_status ?? 'unknown';
            $order->payment_status = array_key_exists($transactionStatus, $statusMap)
                ? $statusMap[$transactionStatus]
                : $transactionStatus;

            $order->payment_method = $status->payment_type ?? 'unknown';
            $order->save();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => $order->payment_status,
                    'badge_class' => match ($order->payment_status) {
                        'paid' => 'badge bg-success',
                        'pending' => 'badge bg-warning text-dark',
                        'failed', 'cancelled', 'expired' => 'badge bg-danger',
                        default => 'badge bg-secondary'
                    },
                ]);
            }

            return redirect()->route('order.detail', ['id' => $order->orders_id])
                ->with('success', 'Payment status updated.');
        } catch (\Exception $e) {
            return $request->ajax()
                ? response()->json(['error' => 'Failed to check payment status'], 500)
                : back()->with('error', 'Failed to check payment status: ' . $e->getMessage());
        }
    }
}
