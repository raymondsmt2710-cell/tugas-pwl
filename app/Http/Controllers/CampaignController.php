<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campaigns = Campaign::active()
            ->paginate(12);
        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('campaigns.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:100000',
            'minimum_donation' => 'nullable|numeric|min:10000',
            'id_category' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'end_date' => 'required|date|after:today',
        ]);

        // ✅ Handle upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('campaigns', 'public');
            $validated['image'] = $path;
        }

        // ✅ Generate slug
        $validated['slug'] = Str::slug($validated['title']);
        
        // ✅ Set user & status
        $validated['user_id'] = auth()->id();
        $validated['campaign_status'] = 'draft';
        $validated['verification_status'] = 'draft';
        $validated['start_date'] = now();
        $validated['collected_amount'] = 0;
        $validated['withdrawn_amount'] = 0;
        $validated['available_balance'] = 0;

        Campaign::create($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign berhasil dibuat! Menunggu persetujuan admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)->firstOrFail();
        
        $donations = $campaign->donations()
            ->successful()  // ✅ Gunakan scope
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
        // ✅ Authorize
        $this->authorize('update', $campaign);
        
        $categories = Category::all();
        return view('campaigns.edit', compact('campaign', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:100000',
            'minimum_donation' => 'nullable|numeric|min:10000',
            'id_category' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url',
            'end_date' => 'required|date|after:today',
        ]);

        // Handle update image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('campaigns', 'public');
            $validated['image'] = $path;
        }

        $campaign->update($validated);

        return redirect()->route('campaigns.show', $campaign->slug)
            ->with('success', 'Campaign berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $campaign)
    {
        $this->authorize('delete', $campaign);
        
        $campaign->delete();
        
        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign berhasil dihapus!');
    }
}