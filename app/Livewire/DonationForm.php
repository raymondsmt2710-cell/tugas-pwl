<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Services\DonationService;
use Livewire\Component;

class DonationForm extends Component
{
    public Campaign $campaign;
    public string $donation_amount = '';
    public string $donor_name = '';
    public string $donor_email = '';
    public string $donor_message = '';
    public bool $is_anonymous = false;

    public function mount(string $slug): void
    {
        $this->campaign = Campaign::where('slug', $slug)->with('user')->firstOrFail();
        if (auth()->check()) {
            $this->donor_name = auth()->user()->full_name ?? '';
            $this->donor_email = auth()->user()->email ?? '';
        }
    }

    protected function rules(): array
    {
        $min = $this->campaign->minimum_donation ?: 1000;
        return [
            'donation_amount' => "required|numeric|min:{$min}|max:999999999",
            'donor_name' => 'required|string|max:100',
            'donor_email' => 'required|email|max:100',
            'donor_message' => 'nullable|string|max:500',
            'is_anonymous' => 'boolean',
        ];
    }

    public function setAmount(int $amount): void
    {
        $this->donation_amount = (string) $amount;
    }

    public function donate(): void
    {
        $this->validate();

        $service = app(DonationService::class);
        $result = $service->createDonation($this->campaign, [
            'donation_amount' => $this->donation_amount,
            'donor_name' => $this->donor_name,
            'donor_email' => $this->donor_email,
            'donor_message' => $this->donor_message,
            'is_anonymous' => $this->is_anonymous,
        ], auth()->user());

        $this->redirect($result['redirect_url']);
    }

    public function render()
    {
        return view('livewire.donation-form');
    }
}
