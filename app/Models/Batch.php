<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class);
    }

    public function dresses(): HasMany
    {
        return $this->hasMany(Dress::class);
    }

    public function pieces()
    {
        return $this->hasMany(Piece::class);
    }

    public function cloth_defects()
    {
        return $this->hasMany(clothDefect::class);
    }
}
