@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Pay Invoice #{{ $invoice->id }}</h2>

        <p><strong>Amount:</strong> ${{ number_format($invoice->amount, 2) }}</p>

        <a href="{{ $checkoutUrl }}" class="btn btn-primary" target="_blank">Pay Now via Stripe</a>

        <div style="margin-top: 20px;">
            <p>Or scan this QR code to pay:</p>
            {!! $qrCode !!}
        </div>
    </div>
@endsection
