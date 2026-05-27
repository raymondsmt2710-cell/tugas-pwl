<?php

namespace App\Http\Controllers;

use App\Services\DonationService;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __construct(
        protected DonationService $donationService,
        protected MidtransService $midtransService
    ) {}

    /**
     * Handle Midtrans payment notification webhook.
     *
     * Midtrans sends notifications for these statuses:
     * - pending: Payment initiated (bank transfer, etc.)
     * - settlement: Payment completed (bank transfer settled)
     * - capture: Credit card payment captured
     * - deny: Payment denied by bank/fraud system
     * - cancel: Payment cancelled by merchant/user
     * - expire: Payment expired (not completed in time)
     */
    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $orderId = $payload['order_id'] ?? 'unknown';

        Log::channel('daily')->info('Midtrans webhook received', [
            'order_id' => $orderId,
            'transaction_status' => $payload['transaction_status'] ?? null,
            'payment_type' => $payload['payment_type'] ?? null,
            'fraud_status' => $payload['fraud_status'] ?? null,
        ]);

        // Validate required fields
        if (empty($payload['order_id']) || empty($payload['transaction_status'])) {
            return response()->json(['message' => 'Missing required fields'], 400);
        }

        // Verify signature to ensure request is from Midtrans
        $isValid = $this->midtransService->verifySignature(
            $payload['order_id'],
            $payload['status_code'] ?? '',
            $payload['gross_amount'] ?? '',
            $payload['signature_key'] ?? ''
        );

        if (!$isValid) {
            Log::channel('daily')->warning('Midtrans webhook INVALID signature', [
                'order_id' => $orderId,
                'ip' => $request->ip(),
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        try {
            $donation = $this->donationService->handlePaymentNotification($payload);

            return response()->json([
                'message' => 'OK',
                'order_id' => $donation->order_id,
                'status' => $donation->payment_status,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::channel('daily')->warning('Midtrans webhook: donation not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        } catch (\Exception $e) {
            Log::channel('daily')->error('Midtrans webhook processing failed', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Internal error'], 500);
        }
    }
}
