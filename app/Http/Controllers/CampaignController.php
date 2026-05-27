<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Models\Campaign;
use App\Models\Category;
use App\Services\CampaignService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function __construct(
        protected CampaignService $campaignService
    ) {}

    /**
     * Display a listing of active campaigns (public).
     */
    public function index(Request $request)
    {
        $query = Campaign::active()
            ->with(['category', 'user']);

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $campaigns = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('campaigns.index', compact('campaigns', 'categories'));
    }

    /**
     * Display the user's own campaigns (dashboard).
     */
    public function myCampaigns()
    {
        $campaigns = Campaign::ownedBy(auth()->user()->id_user)
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('campaigns.my-campaigns', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Campaign::class);

        $categories = Category::all();

        return view('campaigns.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampaignRequest $request)
    {
        $this->authorize('create', Campaign::class);

        $campaign = $this->campaignService->create(
            $request->validated(),
            auth()->user()
        );

        return redirect()->route('campaigns.my')
            ->with('success', 'Kampanye berhasil dibuat! Silakan ajukan untuk review.');
    }

    /**
     * Display the specified resource (public view by slug).
     */
    public function show(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->with(['category', 'user', 'galleries'])
            ->firstOrFail();

        // Check view permission (approved campaigns are public, others need auth)
        if (!$campaign->isApproved() && !$campaign->isCompleted()) {
            $this->authorize('view', $campaign);
        }

        $donations = $campaign->donations()
            ->where('payment_status', 'paid')
            ->latest()
            ->take(10)
            ->get();

        return view('campaigns.show', compact('campaign', 'donations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $categories = Category::all();
        $campaign->load('galleries');

        return view('campaigns.edit', compact('campaign', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $this->campaignService->update($campaign, $request->validated());

        return redirect()->route('campaigns.show', $campaign->fresh()->slug)
            ->with('success', 'Kampanye berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $this->authorize('delete', $campaign);

        $this->campaignService->delete($campaign);

        return redirect()->route('campaigns.my')
            ->with('success', 'Kampanye berhasil dihapus.');
    }

    /**
     * Submit a campaign for admin review.
     */
    public function submit(Campaign $campaign)
    {
        $this->authorize('submit', $campaign);

        $this->campaignService->submitForReview($campaign);

        return redirect()->route('campaigns.my')
            ->with('success', 'Kampanye berhasil diajukan untuk review. Menunggu persetujuan admin.');
    }
}
