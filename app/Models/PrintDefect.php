<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrintDefect extends Model
{
    use HasFactory;

    public function dress()
    {
        return $this->belongsTo(Dress::class);
    }
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }
}
