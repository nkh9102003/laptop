@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-times-circle text-danger" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-4">Payment Cancelled</h2>
                    <p class="lead mb-4">Your payment for Order #{{ $order->id }} has been cancelled.</p>
                    <div class="alert alert-warning mb-4">
                        <p class="mb-0">No charges have been made to your account.</p>
                    </div>
                    <p>You can try again or choose a different payment method.</p>
                    <div class="mt-4">
                        <a href="{{ route('payment.process', $order->id) }}" class="btn btn-primary">Try Again</a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">View Your Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 