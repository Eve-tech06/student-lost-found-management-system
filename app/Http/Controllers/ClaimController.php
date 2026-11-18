<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    /**
     * Display the claim submission form.
     */
    public function create($itemId)
    {
        $item = Item::with([
            'category',
            'location',
            'images'
        ])
            ->where('report_type', 'found')
            ->where('status', '!=', 'returned')
            ->findOrFail($itemId);

        return view('claims.submit', compact('item'));
    }

    /**
     * Process and save a claim.
     */
    public function store(Request $request, $itemId)
    {
        $item = Item::where('report_type', 'found')
            ->where('status', '!=', 'returned')
            ->findOrFail($itemId);

        $validated = $request->validate([
            'claim_description' => [
                'required',
                'string',
                'max:5000'
            ],
        ]);

        $user = $request->user();

        // Prevent duplicate pending claims for the same item.
        $existingClaim = $user->claims()
            ->where('item_id', $item->id)
            ->where('claim_status', 'pending')
            ->exists();

        if ($existingClaim) {
            return redirect()
                ->route('claims.index')
                ->with(
                    'error',
                    'You already have a pending claim for this item.'
                );
        }

        // Save the claim with pending status.
        $user->claims()->create([
            'item_id' => $item->id,
            'claim_description' => $validated['claim_description'],
            'claim_status' => 'pending',
            'claimed_at' => now(),
        ]);

        return redirect()
            ->route('claims.index')
            ->with(
                'success',
                'Your claim has been submitted successfully!'
            );
    }

    /**
     * Display all claims belonging to the logged-in user.
     */
    public function index()
    {
        $claims = auth()->user()
            ->claims()
            ->with([
                'item.category',
                'item.location',
                'item.images'
            ])
            ->latest('claimed_at')
            ->get();

        return view('claims.index', compact('claims'));
    }

    /**
     * Display details of one claim belonging to the logged-in user.
     */
    public function show($claimId)
    {
        $claim = auth()->user()
            ->claims()
            ->with([
                'item.category',
                'item.location',
                'item.images'
            ])
            ->findOrFail($claimId);

        return view('claims.show', compact('claim'));
    }
}
