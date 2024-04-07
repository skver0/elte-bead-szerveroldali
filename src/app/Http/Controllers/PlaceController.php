<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlaceController extends Controller
{
    function index()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        $places = Place::all();

        $places->map(function ($place) {
            $place->image = str_replace('public/', '', $place->image);
            return $place;
        });

        $places->map(function ($place) {
            if (strpos($place->image, 'storage') === false) {
                $place->image = 'storage/' . $place->image;
            }
            return $place;
        });

        return view('places', [
            'places' => $places
        ]);
    }

    function update($id)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $place = Place::findOrFail($id);

        if ($place->image) {
            Storage::disk('public')->delete($place->image);
        }

        $place->update(request()->validate([
            'name' => 'required|string',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg']
        ]));

        if (request()->hasFile('image')) {
            $place->image = request()->file('image')->store('public');
            $place->save();
        }

        return redirect()->route('places');
    }

    function edit($id)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        $place = Place::findOrFail($id);
        return view('places-edit', [
            'place' => $place
        ]);
    }

    function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
        return view('places-create');
    }

    function store(Request $request)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg']
        ]);

        $path = $request->file('image')->store('public');

        Place::create([
            'name' => $request->name,
            'image' => $path
        ]);

        return redirect()->route('places');
    }

    function destroy($id)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $place = Place::findOrFail($id);

        if ($place->image) {
            Storage::disk('public')->delete($place->image);
        }

        $place->delete();
        return redirect()->route('places');
    }
}
