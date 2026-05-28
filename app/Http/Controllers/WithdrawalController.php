<?php

namespace App\Http\Controllers;

use App\Http\Requests\Withdrawal\StoreWithdrawalRequest;
use App\Models\Campaign;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function __construct(
        protected WithdrawalService $withdrawalService
    ) {}

    /**
     * Show withdrawal request form.
     */
    public function create(Request $request)
    {
        $campaigns = Campaign::ownedBy(auth()->user()->id_user)
            ->where('status', 'approved')
            ->where('available_balance', '>', 0)
            ->get();

        $selectedCampaign = null;
        if ($request->filled('campaign')) {
            $selectedCampaign = $campaigns->firstWhere('id_campaign', $request->campaign);
        }

        return view('withdrawals.create', compact('campaigns', 'selectedCampaign'));
    }

    /**
     * Store a withdrawal request (password verified).
     */
    public function store(StoreWithdrawalRequest $request)
    {
        $user = auth()->user();
        $campaign = Campaign::findOrFail($request->id_campaign);

        try {
            $this->withdrawalService->requestWithdrawal(
                $campaign,
                $user,
                $request->validated()
            );

            return redirect()->route('withdrawals.history')
                ->with('success', 'Permintaan penarikan berhasil diajukan. Menunggu persetujuan admin.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Show withdrawal history.
     */
    public function history()
    {
        $withdrawals = $this->withdrawalService->getUserWithdrawals(auth()->user());

        return view('withdrawals.history', compact('withdrawals'));
    }

    /**
     * Cancel a pending withdrawal.
     */
    public function cancel(Withdrawal $withdrawal)
    {
        try {
            $this->withdrawalService->cancel($withdrawal, auth()->user());
            return back()->with('success', 'Penarikan berhasil dibatalkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }
}
