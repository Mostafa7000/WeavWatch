<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\BatchResource\RelationManagers\ClothDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\CuttingDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\DressesRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\IronDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\NeedleDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\OperationDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\PackagingDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\PiecesRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\PreparationDefectsRelationManager;
use App\Filament\Resources\BatchResource\Widgets\ClothDefectsReport;
use App\Models\Batch;
use App\Models\CuttingDefect;
use App\Models\PreparationDefect;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Concerns\HasRelationManagers;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BatchResource extends Resource
{
    use HasRelationManagers;

    protected static ?string $model = Batch::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $activeNavigationIcon = 'heroicon-s-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('بيانات الطلبية')
                    ->schema([
                        Forms\Components\TextInput::make('message_number')
                            ->label('رقم الرسالة'),
                        Forms\Components\TextInput::make('operation_number')
                            ->label('أمر التشغيل'),
                        Forms\Components\TextInput::make('model_number')
                            ->label('اسم الموديل')
                    ])->columns(3),
                Forms\Components\Section::make('الكميات المطلوبة')
                    ->schema([
                        Forms\Components\TextInput::make('required_quantity')
                            ->numeric()
                            ->required()
                            ->label('الكمية المطلوبة'),
                        Forms\Components\Select::make('sizes')
                            ->relationship('sizes', 'title')
                            ->label('المقاسات')
                            ->multiple()
                            ->preload()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('message_number')
                    ->label('رقم الرسالة'),
                Tables\Columns\TextColumn::make('operation_number')
                    ->label('أمر التشغيل'),
                Tables\Columns\TextColumn::make('model_number')
                    ->label('اسم الموديل')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\ViewAction::make()->label('التقارير')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DressesRelationManager::class,
            PiecesRelationManager::class,
            ClothDefectsRelationManager::class,
            PreparationDefectsRelationManager::class,
            CuttingDefectsRelationManager::class,
            NeedleDefectsRelationManager::class,
            OperationDefectsRelationManager::class,
            IronDefectsRelationManager::class,
            PackagingDefectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
            'view' => Pages\ViewBatch::route('/{record}'),
        ];
    }
}
