<?php

namespace App\Http\Requests\Withdrawal;

use Illuminate\Foundation\Http\FormRequest;

class StoreWithdrawalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'id_campaign' => ['required', 'exists:campaigns,id_campaign'],
            'amount' => ['required', 'numeric', 'min:50000'],
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            'account_holder' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'id_campaign' => 'kampanye',
            'amount' => 'jumlah penarikan',
            'bank_name' => 'nama bank',
            'account_number' => 'nomor rekening',
            'account_holder' => 'nama pemilik rekening',
            'notes' => 'catatan',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Minimum penarikan adalah Rp 50.000.',
        ];
    }
}
