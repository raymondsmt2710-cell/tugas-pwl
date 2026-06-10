<?php

namespace App\Livewire;

use App\Models\Campaign;
use App\Models\CampaignComment;
use Livewire\Component;

class CampaignComments extends Component
{
    public $campaignId;
    public $newComment = '';

    protected $rules = [
        'newComment' => 'required|string|max:1000',
    ];

    protected $validationAttributes = [
        'newComment' => 'komentar',
    ];

    public function mount($campaignId)
    {
        $this->campaignId = $campaignId;
    }

    public function storeComment()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->validate();

        CampaignComment::create([
            'id_campaign' => $this->campaignId,
            'id_user' => auth()->id(),
            'comment' => $this->newComment,
        ]);

        $this->reset('newComment');
        session()->flash('success', 'Komentar berhasil ditambahkan!');
    }

    public function deleteComment($commentId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $comment = CampaignComment::findOrFail($commentId);
        $campaign = Campaign::findOrFail($this->campaignId);
        $user = auth()->user();

        // Authorization check
        if ($user->id_user === $comment->id_user || 
            $user->id_user === $campaign->id_user || 
            $user->isAdmin()) {
            
            $comment->delete();
            session()->flash('success', 'Komentar berhasil dihapus!');
            return;
        }

        abort(403, 'Anda tidak memiliki akses untuk menghapus komentar ini.');
    }

    public function render()
    {
        $campaign = Campaign::findOrFail($this->campaignId);
        $comments = CampaignComment::where('id_campaign', $this->campaignId)
            ->with('user')
            ->latest()
            ->get();

        return view('livewire.campaign-comments', [
            'comments' => $comments,
            'campaign' => $campaign,
        ]);
    }
}
