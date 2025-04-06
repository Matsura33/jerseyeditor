<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jersey;
use App\Models\Ornament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JerseyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jerseys = Jersey::with('ornaments')->paginate(10);
        return view('admin.jerseys.index', compact('jerseys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ornaments = Ornament::where('active', true)->get();
        return view('admin.jerseys.create', compact('ornaments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'layer_base' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'layer_shadow' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ornaments' => 'array',
            'ornaments.*.id' => 'exists:ornaments,id',
            'ornaments.*.position_x' => 'required|integer',
            'ornaments.*.position_y' => 'required|integer',
        ]);

        $basePath = $request->file('layer_base')->store('jerseys', 'public');
        $shadowPath = $request->file('layer_shadow')->store('jerseys', 'public');

        $jersey = Jersey::create([
            'name' => $request->name,
            'description' => $request->description,
            'layer_base' => $basePath,
            'layer_shadow' => $shadowPath,
            'active' => $request->has('active'),
        ]);

        if ($request->has('ornaments')) {
            foreach ($request->ornaments as $ornament) {
                $jersey->ornaments()->attach($ornament['id'], [
                    'position_x' => $ornament['position_x'],
                    'position_y' => $ornament['position_y'],
                ]);
            }
        }

        return redirect()->route('jerseys.index')
            ->with('success', 'Jersey created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jersey $jersey)
    {
        $jersey->load('ornaments');
        return view('admin.jerseys.show', compact('jersey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jersey $jersey)
    {
        $ornaments = Ornament::where('active', true)->get();
        $jersey->load('ornaments');
        return view('admin.jerseys.edit', compact('jersey', 'ornaments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jersey $jersey)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'layer_base' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'layer_shadow' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ornaments' => 'array',
            'ornaments.*.id' => 'exists:ornaments,id',
            'ornaments.*.position_x' => 'required|integer',
            'ornaments.*.position_y' => 'required|integer',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'active' => $request->has('active'),
        ];

        if ($request->hasFile('layer_base')) {
            Storage::disk('public')->delete($jersey->layer_base);
            $data['layer_base'] = $request->file('layer_base')->store('jerseys', 'public');
        }

        if ($request->hasFile('layer_shadow')) {
            Storage::disk('public')->delete($jersey->layer_shadow);
            $data['layer_shadow'] = $request->file('layer_shadow')->store('jerseys', 'public');
        }

        $jersey->update($data);

        if ($request->has('ornaments')) {
            $jersey->ornaments()->detach();
            foreach ($request->ornaments as $ornament) {
                $jersey->ornaments()->attach($ornament['id'], [
                    'position_x' => $ornament['position_x'],
                    'position_y' => $ornament['position_y'],
                ]);
            }
        }

        return redirect()->route('jerseys.index')
            ->with('success', 'Jersey updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jersey $jersey)
    {
        Storage::disk('public')->delete([$jersey->layer_base, $jersey->layer_shadow]);
        $jersey->ornaments()->detach();
        $jersey->delete();

        return redirect()->route('jerseys.index')
            ->with('success', 'Jersey deleted successfully.');
    }
}
