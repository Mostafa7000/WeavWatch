<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BatchSize extends Pivot
{
    use HasFactory;

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class,'batch_id');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class,'size_id');
    }
}
