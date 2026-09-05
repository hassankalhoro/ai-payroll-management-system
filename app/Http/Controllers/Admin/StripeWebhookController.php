<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use App\{Invoice};
class StripeWebhookController
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            // Get invoice_number from metadata
            $invoiceNumber = $session->metadata->invoice_number ?? null;

            if ($invoiceNumber) {
                $invoice = Invoice::where('invoice_id', $invoiceNumber)->first();

                if ($invoice) {
                    // Amount paid in cents, convert to dollars
                    $paidAmount = $session->amount_total / 100;

                    // Update invoice
                    $invoice->paidcheck = 1;
                    $invoice->paid_amount = $paidAmount;

                    // If paid amount matches total final amount, set remaining_total to zero
                    if (abs($paidAmount - $invoice->total_famount) < 0.01) {
                        $invoice->remaining_total = 0;
                    }

                    $invoice->updated_at = now();

                    $invoice->save();
                }
            }
        }

        return response('Webhook received', 200);
    }
}
