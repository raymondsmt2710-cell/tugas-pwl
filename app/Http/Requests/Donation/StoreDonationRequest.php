<?php

namespace App\Http\Requests\Donation;

use App\Models\Campaign;
use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Anyone can donate (even guests)
    }

    public function rules(): array
    {
        $campaign = Campaign::where('slug', $this->route('slug'))->first();
        $minDonation = $campaign?->minimum_donation ?: 1000;

        return [
            'donor_name' => ['required', 'string', 'max:100'],
            'donor_email' => ['required', 'email', 'max:100'],
            'donor_message' => ['nullable', 'string', 'max:500'],
            'donation_amount' => ['required', 'numeric', 'min:' . $minDonation, 'max:999999999'],
            'is_anonymous' => ['nullable', 'boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'donor_name' => 'nama donatur',
            'donor_email' => 'email donatur',
            'donor_message' => 'pesan',
            'donation_amount' => 'jumlah donasi',
            'is_anonymous' => 'donasi anonim',
        ];
    }

    public function messages(): array
    {
        return [
            'donation_amount.min' => 'Jumlah donasi minimal Rp :min.',
        ];
    }
}
