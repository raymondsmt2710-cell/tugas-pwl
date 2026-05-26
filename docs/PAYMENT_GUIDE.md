# Payment Guide — Autopahala

## Overview

Autopahala uses **Midtrans** as its payment gateway, specifically the **Snap** integration for a seamless checkout experience. The system is currently configured for **Sandbox** (testing) mode.

## Midtrans Sandbox Integration

### SDK

```json
// composer.json
"midtrans/midtrans-php": "^2.6"
```

### Configuration

**File**: `config/midtrans.php` — Currently empty, needs implementation.

**Recommended configuration**:

```php
<?php
// config/midtrans.php
return [
    'server_key'    => env('MIDTRANS_SERVER_KEY'),
    'client_key'    => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized'  => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds'        => env('MIDTRANS_IS_3DS', true),
];
```

**Environment Variables** (`.env`):

```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxx
MIDTRANS_IS_PRODUCTION=false
```

### Current Implementation

**File**: `app/Services/MidtransService.php`

```php
class MidtransService
{
    public function createTransaction(Donation $donation)
    {
        $snap = new Snap();
        
        $params = [
            'transaction_details' => [
                'order_id' => 'DONATION-' . $donation->id,
                'gross_amount' => $donation->amount,
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'email' => $donation->donor_email,
            ],
            'item_details' => [
                [
                    'id' => $donation->campaign_id,
                    'price' => $donation->amount,
                    'quantity' => 1,
                    'name' => $donation->campaign->title,
                ],
            ],
        ];
        
        return $snap->createTransaction($params);
    }
}
```

**Note**: The class name has a lowercase 'm' (`midtransService`) which should be corrected to `MidtransService`.

## Payment Flow

### Complete Donation Flow (Planned Architecture)

```
┌──────────┐                                                    ┌──────────┐
│  DONOR   │                                                    │ MIDTRANS │
└────┬─────┘                                                    └────┬─────┘
     │                                                               │
     │  1. Fill donation form                                        │
     │  (name, email, amount, message)                               │
     ├──────────────────────────────────────────┐                    │
     │                                          ▼                    │
     │                              ┌───────────────────┐            │
     │                              │DonationController │            │
     │                              │    store()        │            │
     │                              └─────────┬─────────┘            │
     │                                        │                      │
     │                              2. Create Donation               │
     │                                 (status: pending)             │
     │                                        │                      │
     │                              ┌─────────▼─────────┐            │
     │                              │ MidtransService   │            │
     │                              │createTransaction()│            │
     │                              └─────────┬─────────┘            │
     │                                        │                      │
     │                              3. Get Snap Token ──────────────▶│
     │                                        │                      │
     │                              4. Return token ◀────────────────│
     │                                        │                      │
     │◀───────────────────────────────────────┘                      │
     │  5. Redirect to Snap payment page                             │
     │──────────────────────────────────────────────────────────────▶│
     │                                                               │
     │  6. User completes payment                                    │
     │◀──────────────────────────────────────────────────────────────│
     │                                                               │
     │                              7. Webhook notification ─────────│
     │                              ┌───────────────────┐            │
     │                              │MidtransWebhook    │◀───────────│
     │                              │Controller@handle  │            │
     │                              └─────────┬─────────┘            │
     │                                        │                      │
     │                              8. Update donation status        │
     │                              9. Update campaign balance       │
     │                                                               │
```

### Step-by-Step Implementation Guide

#### Step 1: Donation Form Submission

```php
// DonationController@store (to be implemented)
public function store(StoreDonationRequest $request, Campaign $campaign)
{
    $donation = Donation::create([
        'id_campaign' => $campaign->id_campaign,
        'id_user' => auth()->id(),
        'donor_name' => $request->donor_name,
        'donor_email' => $request->donor_email,
        'donor_message' => $request->donor_message,
        'payment_status' => 'pending',
    ]);

    $midtrans = new MidtransService();
    $transaction = $midtrans->createTransaction($donation);

    $donation->update(['payment_token' => $transaction->token]);

    return view('donations.pay', [
        'snapToken' => $transaction->token,
        'donation' => $donation,
    ]);
}
```

#### Step 2: Snap Payment Page (Frontend)

```html
<!-- In Blade template -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            window.location.href = '/donations/' + result.order_id + '/success';
        },
        onPending: function(result) {
            window.location.href = '/donations/' + result.order_id + '/pending';
        },
        onError: function(result) {
            window.location.href = '/donations/' + result.order_id + '/failed';
        }
    });
</script>
```

#### Step 3: Webhook Handler

```php
// MidtransWebhookController@handle (to be implemented)
public function handle(Request $request)
{
    $notification = new \Midtrans\Notification();
    
    $orderId = $notification->order_id;
    $statusCode = $notification->status_code;
    $transactionStatus = $notification->transaction_status;
    $fraudStatus = $notification->fraud_status;

    // Extract donation ID from order_id format: "DONATION-{id}"
    $donationId = str_replace('DONATION-', '', $orderId);
    $donation = Donation::findOrFail($donationId);

    if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
        if ($fraudStatus == 'accept' || !$fraudStatus) {
            $donation->update(['payment_status' => 'success']);
            // Update campaign collected amount
            $donation->campaign->increment('collected_amount', $donation->amount);
        }
    } elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny') {
        $donation->update(['payment_status' => 'failed']);
    } elseif ($transactionStatus == 'expire') {
        $donation->update(['payment_status' => 'cancelled']);
    }

    return response()->json(['status' => 'ok']);
}
```

## Transaction Statuses

### Midtrans Status → App Status Mapping

| Midtrans Status | Fraud Status | App Payment Status |
|----------------|-------------|-------------------|
| `capture` | `accept` | `success` |
| `settlement` | — | `success` |
| `pending` | — | `pending` |
| `cancel` | — | `failed` |
| `deny` | — | `failed` |
| `expire` | — | `cancelled` |
| `capture` | `challenge` | `pending` (manual review) |

## Failure Handling

### Payment Failures

1. **Network timeout**: Snap token has expiry; user can retry
2. **Insufficient funds**: Midtrans returns `deny` → status set to `failed`
3. **Expired payment**: Bank transfer not completed in time → `expire` status
4. **Fraud detection**: `challenge` status requires manual review

### Webhook Failures

1. **Webhook not received**: Implement a scheduled job to check pending donations older than 24h via Midtrans API
2. **Duplicate webhooks**: Use `order_id` as idempotency key — check if already processed
3. **Invalid signature**: Verify webhook signature using server key

### Signature Verification (Recommended)

```php
$serverKey = config('midtrans.server_key');
$hashed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

if ($hashed !== $notification->signature_key) {
    return response()->json(['error' => 'Invalid signature'], 403);
}
```

## Security Considerations

1. **Server Key**: Never expose in frontend code; only use in backend
2. **Client Key**: Safe for frontend (Snap.js)
3. **Webhook URL**: Should be HTTPS in production
4. **Signature Verification**: Always verify webhook signatures
5. **Idempotency**: Handle duplicate webhook notifications gracefully
6. **Amount Validation**: Verify `gross_amount` in webhook matches donation record

## Switching to Production

### Checklist

1. **Get Production Keys**: Apply for production access at [Midtrans Dashboard](https://dashboard.midtrans.com)

2. **Update Environment Variables**:
   ```env
   MIDTRANS_SERVER_KEY=Mid-server-xxxxx    # No "SB-" prefix
   MIDTRANS_CLIENT_KEY=Mid-client-xxxxx    # No "SB-" prefix
   MIDTRANS_IS_PRODUCTION=true
   ```

3. **Update Snap.js URL** in Blade templates:
   ```html
   <!-- Sandbox -->
   <script src="https://app.sandbox.midtrans.com/snap/snap.js">
   
   <!-- Production -->
   <script src="https://app.midtrans.com/snap/snap.js">
   ```

4. **Configure Webhook URL** in Midtrans Dashboard:
   - Sandbox: `https://your-domain.test/api/midtrans/webhook`
   - Production: `https://your-domain.com/api/midtrans/webhook`

5. **Enable HTTPS**: Required for production webhooks

6. **Test thoroughly**: Use Midtrans simulator before going live

7. **Set `is_production` to `true`** in Midtrans SDK configuration:
   ```php
   \Midtrans\Config::$isProduction = config('midtrans.is_production');
   ```

## Midtrans Sandbox Test Cards

| Card Number | Scenario |
|-------------|----------|
| 4811 1111 1111 1114 | Success (3DS) |
| 4911 1111 1111 1113 | Denied |
| 4411 1111 1111 1118 | Challenge (fraud) |

**Expiry**: Any future date  
**CVV**: Any 3 digits  
**OTP**: 112233
