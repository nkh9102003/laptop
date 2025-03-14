@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sales by Product</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Total Quantity</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salesByProduct as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->total_quantity }}</td>
                <td>${{ number_format($product->total_sales, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection