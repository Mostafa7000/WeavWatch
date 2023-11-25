<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeedleDefect extends Model
{
    use HasFactory;

    public function dress() {
        return $this->belongsTo(Dress::class);
    }
    public function size() {
        return $this->belongsTo(Size::class);
    }
}
