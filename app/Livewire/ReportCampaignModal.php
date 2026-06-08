<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\CampaignReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReportCampaignModal extends Component
{
    public Campaign $campaign;
    public bool $showModal = false;
    public string $reason = '';
    public string $description = '';

    protected array $rules = [
        'reason' => 'required|string|in:Penipuan / Kecurangan,Konten Tidak Layak / SARA,Spam / Iklan,Lainnya',
        'description' => 'nullable|string|max:2000',
    ];

    protected array $validationAttributes = [
        'reason' => 'Alasan Pelaporan',
        'description' => 'Deskripsi Tambahan',
    ];

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function openModal()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'));
        }
        $this->resetValidation();
        $this->reset(['reason', 'description']);
        $this->showModal = true;
    }

    public function submitReport()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'));
        }

        $this->validate();

        CampaignReport::create([
            'id_campaign' => $this->campaign->id_campaign,
            'id_user' => Auth::id(),
            'reason' => $this->reason,
            'description' => $this->description,
        ]);

        $this->showModal = false;

        $this->dispatch('toast', message: 'Laporan kampanye berhasil dikirim. Terima kasih atas masukan Anda.', type: 'success');
    }

    public function render()
    {
        return view('livewire.report-campaign-modal');
    }
}
