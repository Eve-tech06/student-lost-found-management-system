<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchApiController extends Controller
{
    private const REPORT_TYPES = ['lost', 'found'];
    private const STATUSES = ['open', 'pending', 'claimed', 'returned'];

    /**
     * GET /api/items
     * All reported items (also accepts the same query params as search/filter).
     */
    public function index(Request $request): JsonResponse
    {
        return $this->listResponse($request);
    }

    /**
     * GET /api/items/search?q=laptop
     */
    public function search(Request $request): JsonResponse
    {
        return $this->listResponse($request);
    }

    /**
     * GET /api/items/filter?report_type=found&category_id=1&location_id=2&status=open
     */
    public function filter(Request $request): JsonResponse
    {
        return $this->listResponse($request);
    }

    /**
     * GET /api/items/{id}
     */
    public function show($id): JsonResponse
    {
        $item = Item::with(['category', 'location', 'images', 'reporter'])->find($id);

        if (! $item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->format($item, true),
        ]);
    }

    private function listResponse(Request $request): JsonResponse
    {
        $items = $this->buildQuery($request)->latest()->get();

        if ($items->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No items found.',
                'count'   => 0,
                'data'    => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'count'   => $items->count(),
            'data'    => $items->map(fn ($item) => $this->format($item))->values(),
        ]);
    }

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

    private function format(Item $item, bool $detailed = false): array
    {
        $data = [
            'id'              => $item->id,
            'item_name'       => $item->item_name,
            'description'     => $item->description,
            'report_type'     => $item->report_type,
            'status'          => $item->status,
            'category'        => $item->category->name ?? null,
            'location'        => $item->location->name ?? null,
            'date_lost_found' => $item->date_lost_found,
            'date_reported'   => $item->created_at?->toDateString(),
            'images'          => $item->images
                ->map(fn ($img) => asset('storage/' . $img->image_path))
                ->values(),
        ];

        if ($detailed) {
            $data['reported_by'] = $item->reporter->name ?? null;
        }

        return $data;
    }
}