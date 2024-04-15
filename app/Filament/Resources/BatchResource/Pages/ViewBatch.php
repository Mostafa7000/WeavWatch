<?php

namespace App\Filament\Resources\BatchResource\Pages;

use App\Filament\Resources\BatchResource;
use App\Models\OperationDefectReport;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBatch extends ViewRecord
{
    protected static string $resource = BatchResource::class;
    protected static ?string $title = 'التقارير';

    protected function getHeaderWidgets(): array
    {
        return [
            BatchResource\Widgets\ClothDefectsReport::class,
            BatchResource\Widgets\CuttingDefectsReport::class,
            BatchResource\Widgets\NeedleDefectsReport::class,
            BatchResource\Widgets\PrintDefectsReport::class,
            BatchResource\Widgets\OperationDefectsReport::class,
            BatchResource\Widgets\IronDefectsReport::class,
            BatchResource\Widgets\PackagingDefectsReport::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|string|array
    {
        return 1;
    }

//    public function getRelationManagers(): array
//    {
//        return [];
//    }
}
