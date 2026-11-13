<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LostItemController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()->items()
            ->where('report_type', 'lost')
            ->with(['category', 'location'])
            ->latest()
            ->paginate(10);

        return view('lost-items.index', compact('items'));
    }

    public function create(): View
    {
        return view('lost-items.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());

        $item = $request->user()->items()->create([
            ...$validated,
            'report_type' => 'lost',
            'status' => 'open',
        ]);

        return redirect()->route('lost-items.show', $item)
            ->with('success', 'Your lost item has been reported.');
    }

    public function show(Request $request, Item $item): View
    {
        $this->ensureOwner($request, $item);
        $item->load(['category', 'location']);

        return view('lost-items.show', compact('item'));
    }

    public function edit(Request $request, Item $item): View
    {
        $this->ensureOwner($request, $item);

        return view('lost-items.edit', [
            'item' => $item,
            ...$this->formOptions(),
        ]);
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        $this->ensureOwner($request, $item);
        $item->update($request->validate($this->rules()));

        return redirect()->route('lost-items.show', $item)
            ->with('success', 'Your lost item report has been updated.');
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

    private function formOptions(): array
    {
        return [
            'categories' => Category::query()->orderBy('category_name')->get(),
            'locations' => Location::query()->orderBy('location_name')->get(),
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
