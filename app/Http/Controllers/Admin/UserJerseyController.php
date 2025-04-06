<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jersey;
use App\Models\Ornament;
use App\Models\OrnamentVersion;
use App\Models\User;
use App\Models\UserJersey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserJerseyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userJerseys = UserJersey::with(['user', 'jersey', 'ornaments'])->paginate(10);
        return view('admin.user-jerseys.index', compact('userJerseys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $jerseys = Jersey::where('active', true)->get();
        $ornaments = Ornament::where('active', true)->with('versions')->get();
        return view('admin.user-jerseys.create', compact('users', 'jerseys', 'ornaments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jersey_id' => 'required|exists:jerseys,id',
            'texture_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'resize_value' => 'required|numeric|min:0.1|max:2',
            'active' => 'boolean',
            'ornaments' => 'array',
            'ornaments.*.id' => 'exists:ornaments,id',
            'ornaments.*.version_id' => 'exists:ornament_versions,id',
            'ornaments.*.position_x' => 'required|integer',
            'ornaments.*.position_y' => 'required|integer',
        ]);

        // Store the texture image
        if ($request->hasFile('texture_url')) {
            $path = $request->file('texture_url')->store('user-jerseys/textures', 'public');
            $validated['texture_url'] = $path;
        }

        $userJersey = UserJersey::create($validated);

        // Attach ornaments with their versions and positions
        if (isset($validated['ornaments'])) {
            foreach ($validated['ornaments'] as $ornamentData) {
                $userJersey->ornaments()->attach($ornamentData['id'], [
                    'ornament_version_id' => $ornamentData['version_id'],
                    'position_x' => $ornamentData['position_x'],
                    'position_y' => $ornamentData['position_y'],
                ]);
            }
        }

        return redirect()->route('user-jerseys.index')
            ->with('success', 'User jersey created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserJersey $userJersey)
    {
        $userJersey->load(['user', 'jersey', 'ornaments.versions']);
        return view('admin.user-jerseys.show', compact('userJersey'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserJersey $userJersey)
    {
        $users = User::all();
        $jerseys = Jersey::where('active', true)->get();
        $ornaments = Ornament::where('active', true)->with('versions')->get();
        $userJersey->load('ornaments');
        return view('admin.user-jerseys.edit', compact('userJersey', 'users', 'jerseys', 'ornaments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserJersey $userJersey)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jersey_id' => 'required|exists:jerseys,id',
            'texture_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'resize_value' => 'required|numeric|min:0.1|max:2',
            'active' => 'boolean',
            'ornaments' => 'array',
            'ornaments.*.id' => 'exists:ornaments,id',
            'ornaments.*.version_id' => 'exists:ornament_versions,id',
            'ornaments.*.position_x' => 'required|integer',
            'ornaments.*.position_y' => 'required|integer',
        ]);

        // Update the texture image if provided
        if ($request->hasFile('texture_url')) {
            // Delete old image
            if ($userJersey->texture_url) {
                Storage::disk('public')->delete($userJersey->texture_url);
            }
            $path = $request->file('texture_url')->store('user-jerseys/textures', 'public');
            $validated['texture_url'] = $path;
        }

        $userJersey->update($validated);

        // Sync ornaments with their versions and positions
        if (isset($validated['ornaments'])) {
            $userJersey->ornaments()->detach();
            foreach ($validated['ornaments'] as $ornamentData) {
                $userJersey->ornaments()->attach($ornamentData['id'], [
                    'ornament_version_id' => $ornamentData['version_id'],
                    'position_x' => $ornamentData['position_x'],
                    'position_y' => $ornamentData['position_y'],
                ]);
            }
        }

        return redirect()->route('user-jerseys.index')
            ->with('success', 'User jersey updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserJersey $userJersey)
    {
        // Delete the texture image
        if ($userJersey->texture_url) {
            Storage::disk('public')->delete($userJersey->texture_url);
        }

        $userJersey->delete();

        return redirect()->route('user-jerseys.index')
            ->with('success', 'User jersey deleted successfully.');
    }
}
