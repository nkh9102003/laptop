<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private $client;

    public function __construct()
    {
        $clientId = config('paypal.client_id');
        $clientSecret = config('paypal.secret');
        $mode = config('paypal.settings.mode');

        if ($mode === 'sandbox') {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        } else {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        }

        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder($orderId, $amount)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $orderId,
                    'description' => 'Order #' . $orderId,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($amount, 2, '.', '')
                    ]
                ]
            ],
            'application_context' => [
                'cancel_url' => route('payment.cancel', ['order' => $orderId]),
                'return_url' => route('payment.success', ['order' => $orderId]),
                'brand_name' => config('app.name'),
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
            ]
        ];

        try {
            $response = $this->client->execute($request);
            return $response;
        } catch (\Exception $e) {
            Log::error('PayPal Create Order Error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function captureOrder($paypalOrderId)
    {
        $request = new OrdersCaptureRequest($paypalOrderId);
        $request->prefer('return=representation');
        
        try {
            $response = $this->client->execute($request);
            return $response;
        } catch (\Exception $e) {
            Log::error('PayPal Capture Order Error: ' . $e->getMessage());
            throw $e;
        }
    }
} 