<?php

namespace App\Http\Controllers;

use App\Http\Requests\Donation\StoreDonationRequest;
use App\Models\Campaign;
use App\Models\Donation;
use App\Services\DonationService;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct(
        protected DonationService $donationService,
        protected MidtransService $midtransService
    ) {}

    /**
     * Show the donation form for a campaign.
     */
    public function create(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->with('user')
            ->firstOrFail();

        if (!$campaign->canAcceptDonations()) {
            return redirect()->route('campaigns.show', $slug)
                ->with('error', 'Kampanye ini tidak dapat menerima donasi saat ini.');
        }

        $snapJsUrl = $this->midtransService->getSnapJsUrl();
        $clientKey = $this->midtransService->getClientKey();

        return view('campaigns.donate', compact('campaign', 'snapJsUrl', 'clientKey'));
    }

    /**
     * Process the donation and return Snap token for inline payment.
     */
    public function store(StoreDonationRequest $request, string $slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        if (!$campaign->canAcceptDonations()) {
            return redirect()->route('campaigns.show', $slug)
                ->with('error', 'Kampanye ini tidak dapat menerima donasi saat ini.');
        }

        try {
            $result = $this->donationService->createDonation(
                $campaign,
                $request->validated(),
                auth()->user()
            );

            // Return view with Snap token for inline payment popup
            return view('donations.pay', [
                'donation' => $result['donation'],
                'snapToken' => $result['snap_token'],
                'snapJsUrl' => $this->midtransService->getSnapJsUrl(),
                'clientKey' => $this->midtransService->getClientKey(),
                'campaign' => $campaign,
            ]);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memproses donasi: ' . $e->getMessage());
        }
    }

    /**
     * Handle return from Midtrans payment page (callback).
     * User is redirected here after completing/cancelling payment.
     */
    public function finish(string $orderId, Request $request)
    {
        $donation = Donation::where('order_id', $orderId)
            ->with('campaign')
            ->firstOrFail();

        // Sync status with Midtrans in case webhook hasn't arrived yet
        $donation = $this->donationService->syncTransactionStatus($donation);

        return view('donations.finish', compact('donation'));
    }

    /**
     * Show donation history for authenticated user (ALL statuses).
     */
    public function history()
    {
        $donations = Donation::byUser(auth()->user()->id_user)
            ->with('campaign')
            ->latest()
            ->paginate(15);

        return view('donations.history', compact('donations'));
    }

    /**
     * Show donation detail / tracking.
     */
    public function track(string $orderId)
    {
        $donation = Donation::where('order_id', $orderId)
            ->with('campaign')
            ->firstOrFail();

        // Sync with Midtrans if still pending
        if ($donation->isPending()) {
            $donation = $this->donationService->syncTransactionStatus($donation);
        }

        return view('donations.track', compact('donation'));
    }

    /**
     * Show all donors for a campaign (public page).
     */
    public function donors(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();

        $donations = Donation::forCampaign($campaign->id_campaign)
            ->successful()
            ->latest()
            ->paginate(20);

        return view('donations.donors', compact('campaign', 'donations'));
    }
}
