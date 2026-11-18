<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FoundItemController extends Controller
{
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

    // Only the owner of a FOUND item may continue; everyone else gets 403.
    private function ownedItem(Item $item): Item
    {
        abort_unless(
            $item->report_type === 'found' && (int) $item->reported_by === (int) Auth::id(),
            403
        );
        return $item;
    }

    private function saveImages(Request $request, Item $item): void
    {
        foreach ($request->file('images', []) as $file) {
            $item->images()->create([
                'image_path' => $file->store('found-items', 'public'),
            ]);
        }
    }

    public function create()
    {
        return view('found-items.create', [
            'categories' => Category::orderBy('category_name')->get(),
            'locations'  => Location::orderBy('location_name')->get(),
        ]);
    }

    public function store(Request $request)
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
            'reported_by'     => Auth::id(),
        ]);

        $this->saveImages($request, $item);

        return redirect()->route('found-items.index')
            ->with('success', 'Found item reported successfully.');
    }

    public function index()
    {
        $items = Item::with(['category', 'location', 'images'])
            ->where('report_type', 'found')
            ->where('reported_by', Auth::id())
            ->latest()
            ->get();

        return view('found-items.index', compact('items'));
    }

    public function show(Item $item)
    {
        $this->ownedItem($item);
        $item->load(['category', 'location', 'images']);

        return view('found-items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $this->ownedItem($item);
        abort_unless($item->status === 'open', 403, 'Only open reports can be edited.');
        $item->load('images');

        return view('found-items.edit', [
            'item'       => $item,
            'categories' => Category::orderBy('category_name')->get(),
            'locations'  => Location::orderBy('location_name')->get(),
        ]);
    }

    public function update(Request $request, Item $item)
    {
        $this->ownedItem($item);
        abort_unless($item->status === 'open', 403, 'Only open reports can be edited.');

        $data = $request->validate($this->rules() + [
            'remove_images'   => 'nullable|array',
            'remove_images.*' => 'integer',
        ]);

        // report_type, status and reported_by are never changed here
        $item->update([
            'item_name'       => $data['item_name'],
            'description'     => $data['description'] ?? null,
            'category_id'     => $data['category_id'],
            'location_id'     => $data['location_id'],
            'date_lost_found' => $data['date_lost_found'],
        ]);

        // Remove ticked images (only this item's own images)
        $toRemove = $item->images()->whereIn('id', $data['remove_images'] ?? [])->get();
        foreach ($toRemove as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $this->saveImages($request, $item);

        return redirect()->route('found-items.show', $item)
            ->with('success', 'Report updated successfully.');
    }
}