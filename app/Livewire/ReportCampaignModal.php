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
    public bool $alreadyReported = false;
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

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign;
        $this->alreadyReported = Auth::check()
            && CampaignReport::where('id_campaign', $campaign->id_campaign)
                             ->where('id_user', Auth::id())
                             ->exists();
    }

    public function openModal(): void
    {
        if (!Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        if ($this->alreadyReported) {
            $this->dispatch('toast', message: 'Anda sudah pernah melaporkan kampanye ini.', type: 'warning');
            return;
        }

        $this->resetValidation();
        $this->reset(['reason', 'description']);
        $this->showModal = true;
    }

    public function submitReport(): void
    {
        if (!Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        // Guard: pastikan belum pernah report kampanye ini
        $exists = CampaignReport::where('id_campaign', $this->campaign->id_campaign)
                                ->where('id_user', Auth::id())
                                ->exists();

        if ($exists) {
            $this->alreadyReported = true;
            $this->showModal = false;
            $this->dispatch('toast', message: 'Anda sudah pernah melaporkan kampanye ini.', type: 'warning');
            return;
        }

        $this->validate();

        CampaignReport::create([
            'id_campaign' => $this->campaign->id_campaign,
            'id_user'     => Auth::id(),
            'reason'      => $this->reason,
            'description' => $this->description,
        ]);

        $this->alreadyReported = true;
        $this->showModal = false;

        $this->dispatch('toast', message: 'Laporan kampanye berhasil dikirim. Terima kasih atas masukan Anda.', type: 'success');
    }

    public function render()
    {
        return view('livewire.report-campaign-modal');
    }
}
