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

        // get url for each image
        foreach ($places as $place) {
            $place->image = Storage::url($place->image);
        }

        return view('places', [
            'places' => $places
        ]);
    }

    function update(int $id)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $place = Place::findOrFail($id);

        request()->validate([
            'name' => ['string', 'required'],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg']
        ]);

        if (request()->hasFile('image')) {
            if (Storage::exists($place->image)) {
                // delete the old image
                Storage::delete($place->image);
            }

            if (request()->hasFile('image')) {
                $path = request()->file('image')->store('public');
                $place->update([
                    'image' => $path
                ]);
            }
        }

        $place->update([
            'name' => request('name')
        ]);

        return redirect()->route('places');
    }

    function edit(int $id)
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
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'required']
        ]);

        $path = $request->file('image')->store('public');

        Place::create([
            'name' => $request->name,
            'image' => $path
        ]);

        return redirect()->route('places');
    }

    function destroy(int $id)
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        $place = Place::findOrFail($id);

        if ($place->image) {
            Storage::disk('public')->delete($place->image);
        }

        // delete all matches regarding place
        $place->contests()->delete();
        $place->delete();
        return redirect()->route('places');
    }
}
