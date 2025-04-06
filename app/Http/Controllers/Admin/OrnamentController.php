<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ornament;
use App\Models\OrnamentVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrnamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ornaments = Ornament::with('versions')->paginate(10);
        return view('admin.ornaments.index', compact('ornaments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ornaments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $ornament = Ornament::create([
            'name' => $request->name,
            'active' => $request->has('active'),
        ]);

        return redirect()->route('ornaments.index')
            ->with('success', 'Ornament created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ornament $ornament)
    {
        return view('admin.ornaments.show', compact('ornament'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ornament $ornament)
    {
        return view('admin.ornaments.edit', compact('ornament'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ornament $ornament)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'active' => $request->has('active'),
        ];

        $ornament->update($data);

        return redirect()->route('ornaments.index')
            ->with('success', 'Ornament updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ornament $ornament)
    {
        // Delete the image file
        Storage::disk('public')->delete($ornament->layer_base);
        
        // Delete all versions
        foreach ($ornament->versions as $version) {
            Storage::disk('public')->delete($version->image_url);
        }
        
        $ornament->delete();

        return redirect()->route('ornaments.index')
            ->with('success', 'Ornament deleted successfully.');
    }

    /**
     * Store a new version for the ornament.
     */
    public function storeVersion(Request $request, Ornament $ornament)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = $request->file('image')->store('ornament-versions', 'public');

        $ornament->versions()->create([
            'name' => $request->name,
            'image_url' => $path,
            'active' => true,
        ]);

        return redirect()->route('admin.ornaments.show', $ornament)
            ->with('success', 'Version added successfully.');
    }

    /**
     * Remove the specified version.
     */
    public function destroyVersion(Ornament $ornament, OrnamentVersion $version)
    {
        Storage::disk('public')->delete($version->image_url);
        $version->delete();

        return redirect()->route('admin.ornaments.show', $ornament)
            ->with('success', 'Version deleted successfully.');
    }
}
