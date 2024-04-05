<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterController extends Controller
{
    function index()
    {
        if (auth()->user()->is_admin)
            $characters = Character::query()->where('user_id', auth()->user()->id)->orWhere('enemy', true)->get();
        else
            $characters = Character::query()->where('user_id', auth()->user()->id)->get();

        return view('dashboard', [
            'characters' => $characters
        ]);
    }

    function create()
    {
        return view('character-create');
    }

    function show($id)
    {
        $character = Character::findOrFail($id);
        $matches = $character->matches;

        // check if the character belongs to the user
        if ($character->user_id !== auth()->id()) {
            abort(403);
        }


        foreach ($matches as $match) {
            // i hate laravel so much you cant even imagine how much i hate it
            // this is so stupid i cant even describe it
            // i really hate it
            // i hate it
            // i hate it
            // i hate it
            // i hate it
            $contest = DB::table('character_contest')
                ->where('character_id', $character->id)
                ->where('contest_id', $match->id)
                ->first();

            $match->enemy = Character::find($contest->enemy_id);
        }

        return view('character', [
            'character' => $character,
            'matches' => $matches
        ]);
    }

    function edit($id)
    {
        $character = Character::findOrFail($id);

        // check if the character belongs to the user
        if ($character->user_id !== auth()->id()) {
            abort(403);
        }

        return view('character-edit', [
            'character' => $character
        ]);
    }

    function update($id)
    {
        $character = Character::findOrFail($id);

        // check if the character belongs to the user
        if ($character->user_id !== auth()->id()) {
            abort(403);
        }

        $character->update(request()->validate([
            'name' => 'required|string',
            'defence' => 'required|integer',
            'strength' => 'required|integer',
            'accuracy' => 'required|integer',
            'magic' => 'required|integer',
            'enemy' => 'string'
        ]));

        if (array_sum(request()->all()) !== 20) {
            return back()->withErrors([
                'defence' => 'The sum of defence, strength, accuracy and magic must be 20.'
            ]);
        }

        if (!isset(request()->all()['enemy'])) {
            $character->enemy = false;
        } else {
            if (auth()->user()->is_admin) {
                if (isset(request()->all()['enemy']))
                    $character->enemy = true;
            }
        }

        $character->update(request()->all());

        return redirect()->route('character', $character->id);
    }

    function destroy($id)
    {
        $character = Character::findOrFail($id);

        // check if the character belongs to the user
        if ($character->user_id !== auth()->id()) {
            abort(403);
        }

        $character->delete();

        return redirect()->route('dashboard');
    }

    function store()
    {
        $data = request()->validate([
            'name' => 'required|string',
            'defence' => 'required|integer',
            'strength' => 'required|integer',
            'accuracy' => 'required|integer',
            'magic' => 'required|integer',
            'enemy' => 'string'
        ]);

        if (array_sum($data) !== 20) {
            return back()->withErrors([
                'defence' => 'The sum of defence, strength, accuracy and magic must be 20.'
            ]);
        }

        if (!isset($data['enemy'])) {
            $data['enemy'] = false;
        } else {
            if (auth()->user()->is_admin) {
                if (isset($data['enemy']))
                    $data['enemy'] = true;
            }
        }

        Character::create([
            'name' => $data['name'],
            'defence' => $data['defence'],
            'strength' => $data['strength'],
            'accuracy' => $data['accuracy'],
            'magic' => $data['magic'],
            'enemy' => $data['enemy'],
            'user_id' => auth()->id()
        ]);

        return redirect()->route('dashboard');
    }
}
