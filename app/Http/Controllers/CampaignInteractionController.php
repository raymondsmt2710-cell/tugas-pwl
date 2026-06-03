<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignLike;
use App\Models\CampaignComment;
use App\Models\CampaignReport;
use Illuminate\Http\Request;

class CampaignInteractionController extends Controller
{
    /**
     * Toggle like / unlike for a campaign.
     */
    public function toggleLike(Campaign $campaign)
    {
        $userId = auth()->id();

        $like = CampaignLike::where('id_campaign', $campaign->id_campaign)
            ->where('id_user', $userId)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            CampaignLike::create([
                'id_campaign' => $campaign->id_campaign,
                'id_user' => $userId,
            ]);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $campaign->likes()->count(),
        ]);
    }

    /**
     * Store a comment for a campaign.
     */
    public function storeComment(Request $request, Campaign $campaign)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        CampaignComment::create([
            'id_campaign' => $campaign->id_campaign,
            'id_user' => auth()->id(),
            'comment' => $request->comment,
        ]);

        return redirect()->to(url()->previous() . '#comments')
            ->with('success', 'Komentar berhasil ditambahkan!');
    }

    /**
     * Store a report for a campaign.
     */
    public function storeReport(Request $request, Campaign $campaign)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        CampaignReport::create([
            'id_campaign' => $campaign->id_campaign,
            'id_user' => auth()->id(),
            'reason' => $request->reason,
            'description' => $request->description,
        ]);

        return redirect()->back()
            ->with('success', 'Laporan kampanye berhasil dikirim. Terima kasih atas masukan Anda.');
    }
}
