<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Campaign;

class CampaignInteraction extends Component
{
    public Campaign $campaign;
    public $commentContent = '';
    public $reportReason = '';
    public $reportDescription = '';
    public $showReportModal = false;

    protected $rules = [
        'commentContent' => 'required|min:3|max:500',
    ];

    public function toggleLike()
    {
        if (!auth()->check()) return redirect()->route('login');

        $like = $this->campaign->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
        } else {
            $this->campaign->likes()->create(['user_id' => auth()->id()]);
        }
        
        $this->campaign->load('likes'); // Refresh data
    }

    public function postComment()
    {
        if (!auth()->check()) return redirect()->route('login');
        $this->validate();

        $this->campaign->comments()->create([
            'user_id' => auth()->id(),
            'content' => $this->commentContent
        ]);

        $this->commentContent = ''; // Reset input
        $this->campaign->load('comments.user'); // Refresh komentar
    }

    public function submitReport()
    {
        if (!auth()->check()) return redirect()->route('login');
        
        $this->validate([
            'reportReason' => 'required',
            'reportDescription' => 'nullable|max:1000'
        ]);

        $this->campaign->reports()->create([
            'user_id' => auth()->id(),
            'reason' => $this->reportReason,
            'description' => $this->reportDescription,
        ]);

        $this->reset(['reportReason', 'reportDescription', 'showReportModal']);
        session()->flash('report_success', 'Laporan Anda berhasil dikirim dan akan ditinjau oleh admin.');
    }

    public function render()
    {
        return view('livewire.campaign-interaction');
    }
}