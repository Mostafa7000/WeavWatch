<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Size extends Model
{
    use HasFactory;

    public function dresses(): BelongsToMany
    {
        return $this->belongsToMany(Dress::class, 'pieces', 'size_id', 'dress_id')
            ->withPivot('value');
    }
}
