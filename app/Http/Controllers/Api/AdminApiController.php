<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Item;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminApiController extends Controller
{
    // Dashboard statistics
    public function dashboard()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_lost_items' => Item::where('report_type', 'lost')->count(),
            'total_found_items' => Item::where('report_type', 'found')->count(),
            'total_items' => Item::count(),
            'total_claims' => Claim::count(),
            'pending_claims' => Claim::where('claim_status', 'pending')->count(),
            'approved_claims' => Claim::where('claim_status', 'approved')->count(),
            'rejected_claims' => Claim::where('claim_status', 'rejected')->count(),
            'returned_items' => Item::where('status', 'returned')->count(),

            'recent_items' => Item::with(['reporter', 'category'])
                ->latest()->take(5)->get(),

            'recent_claims' => Claim::with(['item', 'claimant'])
                ->latest()->take(5)->get(),
        ]);
    }

    // Display and search users
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        return response()->json(
            $query->latest()->paginate(10)
        );
    }

    // Update a user's role
    public function updateUserRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|in:student,staff,admin',
        ]);

        $user = User::findOrFail($id);
        $user->update($validated);

        return response()->json([
            'message' => 'User role updated successfully.',
            'user' => $user,
        ]);
    }

    // Display and filter reported items
    public function items(Request $request)
    {
        $query = Item::with(['reporter', 'category', 'location']);

        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('report_type')) {
            $query->where('report_type', $request->report_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        return response()->json(
            $query->latest()->paginate(10)
        );
    }

    // Display details of a specific item
    public function showItem($id)
    {
        $item = Item::with([
            'reporter',
            'category',
            'location',
            'images',
            'claims.claimant',
        ])->findOrFail($id);

        return response()->json($item);
    }

    // Update item status
    public function updateItemStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,pending,claimed,returned',
        ]);

        $item = Item::findOrFail($id);
        $item->update($validated);

        return response()->json([
            'message' => 'Item status updated successfully.',
            'item' => $item,
        ]);
    }

    // Display and filter claims
    public function claims(Request $request)
    {
        $query = Claim::with(['item', 'claimant', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('claim_status', $request->status);
        }

        return response()->json(
            $query->latest()->paginate(10)
        );
    }

    // Display details of a specific claim
    public function claimDetails($id)
    {
        $claim = Claim::with([
            'item.images',
            'claimant',
            'reviewer',
        ])->findOrFail($id);

        return response()->json($claim);
    }

    // Approve a claim
    public function approveClaim($id)
    {
        return DB::transaction(function () use ($id) {

            $claim = Claim::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($claim->claim_status !== 'pending') {
                return response()->json([
                    'message' => 'This claim has already been reviewed.',
                ], 422);
            }

            $item = Item::whereKey($claim->item_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($item->status, ['claimed', 'returned'])) {
                return response()->json([
                    'message' => 'This item is no longer available.',
                ], 422);
            }

            $claim->update([
                'claim_status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            $item->update([
                'status' => 'claimed',
            ]);

            return response()->json([
                'message' => 'Claim approved successfully.',
                'claim' => $claim->fresh(),
            ]);
        });
    }

    // Reject a claim
    public function rejectClaim($id)
    {
        return DB::transaction(function () use ($id) {

            $claim = Claim::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($claim->claim_status !== 'pending') {
                return response()->json([
                    'message' => 'This claim has already been reviewed.',
                ], 422);
            }

            $claim->update([
                'claim_status' => 'rejected',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Claim rejected successfully.',
                'claim' => $claim->fresh(),
            ]);
        });
    }

    // Generate reports
    public function reports()
    {
        return response()->json([
            'items_by_type' => Item::select(
                'report_type',
                DB::raw('COUNT(*) as total')
            )->groupBy('report_type')->get(),

            'items_by_status' => Item::select(
                'status',
                DB::raw('COUNT(*) as total')
            )->groupBy('status')->get(),

            'claims_by_status' => Claim::select(
                'claim_status',
                DB::raw('COUNT(*) as total')
            )->groupBy('claim_status')->get(),

            'users_by_role' => User::select(
                'role',
                DB::raw('COUNT(*) as total')
            )->groupBy('role')->get(),
        ]);
    }
}