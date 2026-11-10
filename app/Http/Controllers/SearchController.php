<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private const REPORT_TYPES = ['lost', 'found'];
    private const STATUSES = ['open', 'pending', 'claimed', 'returned'];

    /**
     * Browse page. Also handles keyword search and filters,
     * because search() and filter() both reuse this method.
     */
    public function index(Request $request)
    {
        $items = $this->buildQuery($request)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('items.index', [
            'items'       => $items,
            'categories'  => Category::orderBy('name')->get(),
            'locations'   => Location::orderBy('name')->get(),
            'reportTypes' => self::REPORT_TYPES,
            'statuses'    => self::STATUSES,
            'filters'     => $request->only(['q', 'report_type', 'category_id', 'location_id', 'status']),
        ]);
    }

    /**
     * Keyword search (item name or description).
     */
    public function search(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Filter by report type, category, location and status.
     */
    public function filter(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Single item details page.
     */
    public function show($id)
    {
        $item = Item::with(['category', 'location', 'images', 'reporter'])
            ->findOrFail($id);

        // Claim button is only for found items that are still open.
        $canClaim = $item->report_type === 'found'
            && $item->status === 'open';

        return view('items.show', compact('item', 'canClaim'));
    }

    /**
     * Shared query builder for keyword + filters.
     */
    private function buildQuery(Request $request)
    {
        $query = Item::with(['category', 'location', 'images']);

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('item_name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('report_type') && in_array($request->report_type, self::REPORT_TYPES)) {
            $query->where('report_type', $request->report_type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('status') && in_array($request->status, self::STATUSES)) {
            $query->where('status', $request->status);
        }

        return $query;
    }
}