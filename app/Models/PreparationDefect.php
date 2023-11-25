<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreparationDefect extends Model
{
    use HasFactory;

    public function dress()
    {
        return $this->belongsTo(Dress::class);
    }
}
