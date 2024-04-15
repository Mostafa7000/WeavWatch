<?php

namespace App\Filament\Resources\BatchResource\Widgets;

use App\Models\Batch;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractWidget extends Widget
{
    public ?Model $record = null;
    public function __construct(Batch $batch = null)
    {
        $this->record = $batch;
    }
}