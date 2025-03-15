@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-4">Payment Successful!</h2>
                    <p class="lead mb-4">Your payment for Order #{{ $order->id }} has been processed successfully.</p>
                    <div class="alert alert-success mb-4">
                        <p class="mb-0">Amount paid: ${{ number_format($order->total, 2) }}</p>
                    </div>
                    <p>Thank you for your purchase. You will receive a confirmation email shortly.</p>
                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">View Your Orders</a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 