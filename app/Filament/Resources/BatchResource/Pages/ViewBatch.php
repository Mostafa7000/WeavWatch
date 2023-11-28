<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBatch extends ViewRecord
{
    protected static string $resource = BatchResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            BatchResource\Widgets\ClothDefectsReport::class,
            BatchResource\Widgets\PreparationDefectsReport::class,
            BatchResource\Widgets\NeedleDefectsReport::class,
            BatchResource\Widgets\IronDefectsReport::class,
            BatchResource\Widgets\PackagingDefectsReport::class,

        ];
    }

    public function getHeaderWidgetsColumns(): int|string|array
    {
        return 2;
    }
}
