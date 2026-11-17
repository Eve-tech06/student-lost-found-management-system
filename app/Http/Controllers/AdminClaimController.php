<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Illuminate\Support\Facades\DB;

class AdminClaimController extends Controller
{
    // Approve a claim
    public function approve($id)
    {
        $claim = Claim::findOrFail($id);

        if ($claim->claim_status !== 'pending') {
            return back()->with('error', 'This claim has already been reviewed.');
        }

        DB::transaction(function () use ($claim) {

            $claim->update([
                'claim_status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            $claim->item->update([
                'status' => 'claimed',
            ]);
        });

        return back()->with('success', 'Claim approved successfully.');
    }

    // Reject a claim
    public function reject($id)
    {
        $claim = Claim::findOrFail($id);

        if ($claim->claim_status !== 'pending') {
            return back()->with('error', 'This claim has already been reviewed.');
        }

        $claim->update([
            'claim_status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Claim rejected successfully.');
    }
}
