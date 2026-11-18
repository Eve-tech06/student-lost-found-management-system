<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LostItemApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = $request->user()->items()
            ->where('report_type', 'lost')
            ->with(['category', 'location'])
            ->latest()
            ->paginate(10);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $item = $request->user()->items()->create([
            ...$validated,
            'report_type' => 'lost',
            'status' => 'open',
        ])->load(['category', 'location']);

        return response()->json(['data' => $item], 201);
    }

    public function show(Request $request, Item $item): JsonResponse
    {
        $this->ensureOwner($request, $item);

        return response()->json(['data' => $item->load(['category', 'location'])]);
    }

    public function update(Request $request, Item $item): JsonResponse
    {
        $this->ensureOwner($request, $item);
        $item->update($request->validate($this->rules()));

        return response()->json(['data' => $item->load(['category', 'location'])]);
    }

    public function destroy(Request $request, Item $item): JsonResponse
    {
        $this->ensureOwner($request, $item);
        $item->delete();

        return response()->json(['message' => 'Lost item report deleted.']);
    }

    private function rules(): array
    {
        return [
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'date_lost_found' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    private function ensureOwner(Request $request, Item $item): void
    {
        abort_unless(
            $item->report_type === 'lost' && (int) $item->reported_by === (int) $request->user()->id,
            404
        );
    }
}
