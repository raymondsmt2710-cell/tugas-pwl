<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Donation;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignDonorsList extends Component
{
    use WithPagination;

    public Campaign $campaign;
    public string $search = '';
    public int $perPage = 20;

    public function mount(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function loadMore()
    {
        $this->perPage += 20;
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->perPage = 20;
    }

    public function render()
    {
        $query = Donation::forCampaign($this->campaign->id_campaign)
            ->successful()
            ->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('donor_name', 'like', '%' . $this->search . '%')
                  ->orWhere('donor_message', 'like', '%' . $this->search . '%');
            });
        }

        $donations = $query->paginate($this->perPage);

        return view('livewire.campaign-donors-list', compact('donations'));
    }
}
