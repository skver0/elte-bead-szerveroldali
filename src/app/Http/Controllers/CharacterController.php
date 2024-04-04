<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    function index()
    {
        $characters = Character::query()->where('user_id', auth()->user()->id)->orWhere('enemy', true)->get();

        return view('dashboard', [
            'characters' => $characters
        ]);
    }
}
