<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function processPayment(Order $order)
    {
        // Check if order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Unauthorized access to order.');
        }

        // Check if order is already paid
        if ($order->status === 'paid') {
            return redirect()->route('orders.index')->with('info', 'This order has already been paid.');
        }

        try {
            // Create PayPal order
            $response = $this->paypalService->createOrder($order->id, $order->total);
            
            // Get approval link
            $approvalLink = null;
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    $approvalLink = $link->href;
                    break;
                }
            }

            if (!$approvalLink) {
                throw new \Exception('No approval link found in PayPal response.');
            }

            // Store PayPal order ID in session
            session(['paypal_order_id' => $response->result->id]);
            
            // Redirect to PayPal
            return redirect($approvalLink);
        } catch (\Exception $e) {
            Log::error('PayPal payment process error: ' . $e->getMessage());
            return redirect()->route('orders.index')->with('error', 'There was an error processing your payment. Please try again later.');
        }
    }

    public function success(Request $request, Order $order)
    {
        // Check if order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'Unauthorized access to order.');
        }

        // Get PayPal order ID from session
        $paypalOrderId = session('paypal_order_id');
        if (!$paypalOrderId) {
            return redirect()->route('orders.index')->with('error', 'Payment session expired.');
        }

        try {
            DB::beginTransaction();

            // Capture the payment
            $response = $this->paypalService->captureOrder($paypalOrderId);
            
            // Update payment record
            $payment = Payment::where('order_id', $order->id)->first();
            if ($payment) {
                $payment->update([
                    'transaction_id' => $response->result->id,
                    'payer_id' => $response->result->payer->payer_id ?? null,
                    'payer_email' => $response->result->payer->email_address ?? null,
                    'status' => $response->result->status,
                ]);
            }

            // Update order status
            $order->update(['status' => 'paid']);

            DB::commit();

            // Clear session
            session()->forget('paypal_order_id');

            return view('payments.success', compact('order'));
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PayPal payment capture error: ' . $e->getMessage());
            return redirect()->route('orders.index')->with('error', 'There was an error capturing your payment. Please contact support.');
        }
    }

    public function cancel(Request $request, Order $order)
    {
        // Clear session
        session()->forget('paypal_order_id');

        return view('payments.cancel', compact('order'));
    }
} 