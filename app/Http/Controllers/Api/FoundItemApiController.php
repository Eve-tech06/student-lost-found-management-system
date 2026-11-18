<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoundItemApiController extends Controller
{
    private function format(Item $item): array
    {
        $item->loadMissing(['category', 'location', 'images']);

        return [
            'id'          => $item->id,
            'item_name'   => $item->item_name,
            'description' => $item->description,
            'report_type' => $item->report_type,
            'status'      => $item->status,
            'category'    => ['id' => $item->category_id, 'name' => $item->category->category_name],
            'location'    => ['id' => $item->location_id, 'name' => $item->location->location_name],
            'date_found'  => $item->date_lost_found->toDateString(),
            'reported_at' => $item->created_at,
            'images'      => $item->images->map(fn ($i) => [
                'id'  => $i->id,
                'url' => Storage::disk('public')->url($i->image_path),
            ])->values(),
        ];
    }

    private function rules(): array
    {
        return [
            'item_name'       => 'required|string|max:255',
            'description'     => 'nullable|string|max:5000',
            'category_id'     => 'required|exists:categories,id',
            'location_id'     => 'required|exists:locations,id',
            'date_lost_found' => 'required|date|before_or_equal:today',
            'images'          => 'nullable|array|max:5',
            'images.*'        => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    private function forbidden(): JsonResponse
    {
        return response()->json(['message' => 'You do not have access to this report.'], 403);
    }

    private function isOwner(Request $request, Item $item): bool
    {
        return $item->report_type === 'found'
            && (int) $item->reported_by === (int) $request->user()->id;
    }

    public function index(Request $request): JsonResponse
    {
        $items = Item::with(['category', 'location', 'images'])
            ->where('report_type', 'found')
            ->where('reported_by', $request->user()->id)
            ->latest()
            ->get();

        return response()->json(['data' => $items->map(fn ($i) => $this->format($i))]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate($this->rules());

        $item = Item::create([
            'item_name'       => $data['item_name'],
            'description'     => $data['description'] ?? null,
            'category_id'     => $data['category_id'],
            'location_id'     => $data['location_id'],
            'date_lost_found' => $data['date_lost_found'],
            'report_type'     => 'found',
            'status'          => 'open',
            'reported_by'     => $request->user()->id,
        ]);

        foreach ($request->file('images', []) as $file) {
            $item->images()->create(['image_path' => $file->store('found-items', 'public')]);
        }

        return response()->json([
            'message' => 'Found item reported successfully.',
            'data'    => $this->format($item),
        ], 201);
    }

    public function show(Request $request, Item $item): JsonResponse
    {
        if (! $this->isOwner($request, $item)) {
            return $this->forbidden();
        }

        return response()->json(['data' => $this->format($item)]);
    }

    public function update(Request $request, Item $item): JsonResponse
    {
        if (! $this->isOwner($request, $item)) {
            return $this->forbidden();
        }
        if ($item->status !== 'open') {
            return response()->json(['message' => 'Only open reports can be edited.'], 403);
        }

        $data = $request->validate($this->rules());

        $item->update([
            'item_name'       => $data['item_name'],
            'description'     => $data['description'] ?? null,
            'category_id'     => $data['category_id'],
            'location_id'     => $data['location_id'],
            'date_lost_found' => $data['date_lost_found'],
        ]);

        foreach ($request->file('images', []) as $file) {
            $item->images()->create(['image_path' => $file->store('found-items', 'public')]);
        }

        return response()->json([
            'message' => 'Report updated successfully.',
            'data'    => $this->format($item->fresh()),
        ]);
    }
}