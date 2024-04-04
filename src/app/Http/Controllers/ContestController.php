<?php

namespace App\Http\Controllers;

use App\Models\Contest;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    function show($id)
    {
        $match = Contest::findOrFail($id);
        $characters = $match->characters;

        return view('match', [
            'match' => $match,
            'characters' => $characters
        ]);
    }

    function create()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }

        return view('match-create');
    }
}
