<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OperatorPackageController extends Controller
{
    private function authorizeOperator(Request $request): void
    {
        abort_if($request->user()->role !== 'operator', 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeOperator($request);

        return view('operator.packages', ['packages' => Package::withCount('bookings')->latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeOperator($request);
        $data = $this->validated($request);
        $data['image_url'] = Storage::url($request->file('image')->store('packages', 'public'));
        Package::create($data);

        return back()->with('success', 'Package created successfully.');
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $this->authorizeOperator($request);
        $data = $this->validated($request, $package);
        if ($request->hasFile('image')) {
            $this->deleteLocalImage($package->image_url);
            $data['image_url'] = Storage::url($request->file('image')->store('packages', 'public'));
        }
        $package->update($data);

        return back()->with('success', 'Package updated successfully.');
    }

    public function destroy(Request $request, Package $package): RedirectResponse
    {
        $this->authorizeOperator($request);

        if ($package->bookings()->exists()) {
            $package->update(['is_available' => false]);

            return back()->with('success', 'Package archived because it has booking records.');
        }

        $this->deleteLocalImage($package->image_url);
        $package->delete();

        return back()->with('success', 'Package deleted successfully.');
    }

    private function validated(Request $request, ?Package $package = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('packages')->ignore($package)],
            'location' => ['required', 'string', 'max:150'],
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'string', 'max:60'],
            'difficulty' => ['required', 'string', 'max:60'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'available_dates' => ['nullable', 'array', 'max:5'],
            'available_dates.*' => ['nullable', 'date', 'after:today', 'distinct'],
            'image' => [$package ? 'nullable' : 'required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
        unset($data['image']);
        $data['available_dates'] = collect($data['available_dates'] ?? [])->filter()->unique()->sort()->values()->all();
        $data['is_available'] = $request->boolean('is_available');

        return $data;
    }

    private function deleteLocalImage(string $url): void
    {
        if (str_starts_with($url, '/storage/packages/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $url));
        }
    }
}
