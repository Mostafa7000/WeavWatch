<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DressResource\Pages;
use App\Filament\Resources\DressResource\RelationManagers;
use App\Models\Dress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DressResource extends Resource
{
    protected static ?string $model = Dress::class;
    //protected static bool $shouldRegisterNavigation= false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('الكود'),
                Tables\Columns\TextColumn::make('color.title')
                    ->label('اللون')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            RelationManagers\PiecesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDresses::route('/'),
            'create' => Pages\CreateDress::route('/create'),
            'edit' => Pages\EditDress::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
        return false;
    }

}
