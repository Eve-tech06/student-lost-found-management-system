<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Claim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Admin dashboard
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_lost_items' => Item::where('report_type', 'lost')->count(),
            'total_found_items' => Item::where('report_type', 'found')->count(),
            'total_items' => Item::count(),
            'total_claims' => Claim::count(),
            'pending_claims' => Claim::where('claim_status', 'pending')->count(),
            'approved_claims' => Claim::where('claim_status', 'approved')->count(),
            'rejected_claims' => Claim::where('claim_status', 'rejected')->count(),
            'returned_items' => Item::where('status', 'returned')->count(),
        ];

        $recentItems = Item::with(['reporter', 'category'])
            ->latest()
            ->take(5)
            ->get();

        $recentClaims = Claim::with(['item', 'claimant'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentItems',
            'recentClaims'
        ));
    }

    // View and search users
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

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users', compact('users'));
    }

    // View individual user
    public function showUser($id)
    {
        $user = User::with(['items', 'claims'])->findOrFail($id);

        return view('admin.user-show', compact('user'));
    }

    // Update user role
    public function updateUserRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|in:student,staff,admin',
        ]);

        $user = User::findOrFail($id);

        $user->update($validated);

        return back()->with('success', 'User role updated successfully.');
    }

    // View, search and filter items
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

        $items = $query->latest()->paginate(10)->withQueryString();

        return view('admin.items', compact('items'));
    }

    // View individual item
    public function showItem($id)
    {
        $item = Item::with([
            'reporter',
            'category',
            'location',
            'images',
            'claims.claimant',
        ])->findOrFail($id);

        return view('admin.item-show', compact('item'));
    }

    // Update item status
    public function updateItemStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:open,pending,claimed,returned',
        ]);

        $item = Item::findOrFail($id);

        $item->update($validated);

        return back()->with('success', 'Item status updated successfully.');
    }

    // View and filter claims
    public function claims(Request $request)
    {
        $query = Claim::with(['item', 'claimant', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('claim_status', $request->status);
        }

        $claims = $query->latest()->paginate(10)->withQueryString();

        return view('admin.claims', compact('claims'));
    }

    // View individual claim
    public function claimDetails($id)
    {
        $claim = Claim::with([
            'item.images',
            'claimant',
            'reviewer',
        ])->findOrFail($id);

        return view('admin.claim-details', compact('claim'));
    }

    // Approve a claim
    public function approveClaim($id)
    {
        $claim = Claim::findOrFail($id);

        if ($claim->claim_status !== 'pending') {
            return back()->with('error', 'This claim has already been reviewed.');
        }

        DB::transaction(function () use ($claim) {
            $claim = Claim::whereKey($claim->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($claim->claim_status !== 'pending') {
                throw new \RuntimeException('This claim has already been reviewed.');
            }

            $item = Item::whereKey($claim->item_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array($item->status, ['claimed', 'returned'])) {
                throw new \RuntimeException('This item is no longer available.');
            }

            $claim->update([
                'claim_status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);

            $item->update(['status' => 'claimed']);
        });

        return back()->with('success', 'Claim approved successfully.');
    }

    // Reject a claim
    public function rejectClaim($id)
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

    // Reports and statistics
    public function reports()
    {
        $itemsByType = Item::select('report_type', DB::raw('COUNT(*) as total'))
            ->groupBy('report_type')
            ->get();

        $itemsByStatus = Item::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $claimsByStatus = Claim::select('claim_status', DB::raw('COUNT(*) as total'))
            ->groupBy('claim_status')
            ->get();

        $usersByRole = User::select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->get();

        return view('admin.reports', compact(
            'itemsByType',
            'itemsByStatus',
            'claimsByStatus',
            'usersByRole'
        ));
    }
}