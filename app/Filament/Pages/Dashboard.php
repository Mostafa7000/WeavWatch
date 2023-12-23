<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DashboardPhoto;
use App\Filament\Widgets\DashboardText;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $activeNavigationIcon = 'heroicon-s-home';

    public function getWidgets(): array
    {
        return [
            DashboardText::class,
            DashboardPhoto::class,
        ];
    }
    public function getColumns(): int|string|array
    {
        return 1;
    }
}