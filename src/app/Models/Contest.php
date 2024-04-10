<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

// this is "Match" but it's a reserved word in PHP
// so we'll use "Contest" instead
// i love how this wasn't tested but it's in
// the description of the task :)))

class Contest extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'win',
        'history',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'win' => 'boolean',
            'history' => 'array',
        ];
    }

    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class)->withPivot('enemy_id', 'character_id', 'contest_id', 'hero_hp', 'enemy_hp');
    }

    public function place(): BelongsTo
    {
        return $this->belongsTo(Place::class);
    }
}
