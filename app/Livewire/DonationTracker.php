<?php

namespace App\Livewire;

use App\Models\Donation;
use App\Services\DonationService;
use Livewire\Component;

class DonationTracker extends Component
{
    public string $orderId;
    public Donation $donation;

    public function mount(string $orderId)
    {
        $this->orderId = $orderId;
        $this->loadDonation();
    }

    public function loadDonation()
    {
        $this->donation = Donation::where('order_id', $this->orderId)
            ->with('campaign')
            ->firstOrFail();
    }

    public function syncStatus(DonationService $donationService)
    {
        if ($this->donation->isPending()) {
            $this->donation = $donationService->syncTransactionStatus($this->donation);
            
            if ($this->donation->isPaid()) {
                $this->dispatch('toast', message: 'Pembayaran berhasil dikonfirmasi!', type: 'success');
            } elseif ($this->donation->isExpired() || $this->donation->isFailed()) {
                $this->dispatch('toast', message: 'Pembayaran kedaluwarsa atau gagal.', type: 'error');
            }
        }
    }

    public function render()
    {
        return view('livewire.donation-tracker');
    }
}
