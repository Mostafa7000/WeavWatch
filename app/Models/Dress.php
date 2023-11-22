<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dress extends Model
{
    use HasFactory;

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function pieces()
    {
        return $this->hasMany(Piece::class);
    }
}
