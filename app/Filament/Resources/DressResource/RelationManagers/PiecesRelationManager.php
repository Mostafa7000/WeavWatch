<?php

namespace App\Filament\Resources\DressResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PiecesRelationManager extends RelationManager
{
    protected static string $relationship = 'pieces';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('size_id')
                    ->relationship('size', 'title')
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('value')
                ->required()
                ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('size_id')
            ->columns([
                Tables\Columns\TextColumn::make('size_id'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
}
