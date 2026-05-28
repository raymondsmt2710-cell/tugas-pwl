<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Services\WithdrawalService;
use Livewire\Component;

class WithdrawalForm extends Component
{
    public ?int $campaignId = null;
    public string $id_campaign = '';
    public string $amount = '';
    public string $bank_name = '';
    public string $account_number = '';
    public string $account_holder = '';
    public string $notes = '';

    public function mount(?int $campaignId = null): void
    {
        if ($campaignId) {
            $this->id_campaign = (string) $campaignId;
        }
    }

    protected function rules(): array
    {
        return [
            'id_campaign' => 'required|exists:campaigns,id_campaign',
            'amount' => 'required|numeric|min:50000',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function getAvailableBalanceProperty(): float
    {
        if (!$this->id_campaign) return 0;
        $campaign = Campaign::find($this->id_campaign);
        return $campaign ? (float) $campaign->available_balance : 0;
    }

    public function getCampaignsProperty()
    {
        return Campaign::ownedBy(auth()->user()->id_user)
            ->where('status', 'approved')
            ->where('available_balance', '>', 0)
            ->get();
    }

    public function submit(): void
    {
        $this->validate();

        $campaign = Campaign::findOrFail($this->id_campaign);
        $service = app(WithdrawalService::class);

        try {
            $service->requestWithdrawal($campaign, auth()->user(), [
                'amount' => $this->amount,
                'bank_name' => $this->bank_name,
                'account_number' => $this->account_number,
                'account_holder' => $this->account_holder,
                'notes' => $this->notes,
            ]);

            session()->flash('success', 'Permintaan penarikan berhasil diajukan.');
            $this->redirect(url('/withdrawals/history'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
        }
    }

    public function render()
    {
        return view('livewire.withdrawal-form');
    }
}
