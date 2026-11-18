<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ClaimApiController extends Controller
{
    public function store(Request $request, $itemId)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $item = Item::where('report_type', 'found')
            ->where('status', '!=', 'returned')
            ->find($itemId);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Found item not available for claiming.'
            ], 404);
        }

        $validated = $request->validate([
            'claim_description' => [
                'required',
                'string',
                'max:5000'
            ],
        ]);

        $existingClaim = $user->claims()
            ->where('item_id', $item->id)
            ->where('claim_status', 'pending')
            ->exists();

        if ($existingClaim) {
            return response()->json([
                'success' => false,
                'message' => 'You already have a pending claim for this item.'
            ], 409);
        }

        $claim = $user->claims()->create([
            'item_id' => $item->id,
            'claim_description' => $validated['claim_description'],
            'claim_status' => 'pending',
            'claimed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Claim submitted successfully.',
            'claim' => $claim
        ], 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $claims = $user->claims()
            ->with('item')
            ->latest('claimed_at')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Your claims retrieved successfully.',
            'claims' => $claims
        ], 200);
    }

    public function show(Request $request, $claimId)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $claim = $user->claims()
            ->with('item')
            ->find($claimId);

        if (!$claim) {
            return response()->json([
                'success' => false,
                'message' => 'Claim not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Claim details retrieved successfully.',
            'claim' => $claim
        ], 200);
    }
}
