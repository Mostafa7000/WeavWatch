<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Batch extends Model
{
    use HasFactory;

    public function sizeQuantity(): HasMany
    {
        return $this->hasMany(BatchSize::class);
    }

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

    public function print_defects()
    {
        return $this->hasMany(PrintDefect::class);
    }

    public function iron_defects()
    {
        return $this->hasMany(IronDefect::class);
    }

    public function needle_defects()
    {
        return $this->hasMany(NeedleDefect::class);
    }

    public function packaging_defects()
    {
        return $this->hasMany(PackagingDefect::class);
    }

    public function cutting_defects()
    {
        return $this->hasMany(CuttingDefect::class);
    }

    public function operation_defects()
    {
        return $this->hasMany(OperationDefectReport::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
