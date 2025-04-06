<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserJersey;
use App\Models\Ornament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserJerseyController extends Controller
{
    public function index()
    {
        return Auth::user()->userJerseys()->with('jersey')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jersey_id' => 'required|exists:jerseys,id',
            'texture_url' => 'required|url',
            'texture_size' => 'required|integer|min:0|max:200',
            'prompt' => 'nullable|string',
            'ornaments_data' => 'required|array'
        ]);

        $userJersey = Auth::user()->userJerseys()->create($validated);

        return response()->json($userJersey, 201);
    }

    public function show(UserJersey $userJersey)
    {
        $this->authorize('view', $userJersey);
        return $userJersey->load('jersey');
    }

    public function update(Request $request, UserJersey $userJersey)
    {
        $this->authorize('update', $userJersey);

        $validated = $request->validate([
            'texture_url' => 'sometimes|required|url',
            'texture_size' => 'sometimes|required|integer|min:0|max:200',
            'prompt' => 'nullable|string',
            'ornaments_data' => 'sometimes|required|array'
        ]);

        $userJersey->update($validated);

        return response()->json($userJersey);
    }

    public function destroy(UserJersey $userJersey)
    {
        $this->authorize('delete', $userJersey);
        $userJersey->delete();
        return response()->noContent();
    }

    public function getOrnamentVersions(Ornament $ornament)
    {
        return response()->json([
            'versions' => $ornament->versions
        ]);
    }
} 