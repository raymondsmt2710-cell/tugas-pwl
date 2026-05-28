<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class CampaignFilter extends Component
{
    use WithPagination;

    public string $search = '';
    public string $category = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $campaigns = Campaign::active()
            ->with(['category', 'user'])
            ->search($this->search ?: null)
            ->when($this->category, fn ($q) => $q->byCategory($this->category))
            ->latest()
            ->paginate(12);

        return view('livewire.campaign-filter', [
            'campaigns' => $campaigns,
            'categories' => Category::all(),
        ]);
    }
}
