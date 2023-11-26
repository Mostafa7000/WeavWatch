<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationDefectReport extends Model
{
    use HasFactory;

    public function dress()
    {
        return $this->belongsTo(Dress::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function defect()
    {
        return $this->belongsTo(OperationDefect::class);
    }
}
