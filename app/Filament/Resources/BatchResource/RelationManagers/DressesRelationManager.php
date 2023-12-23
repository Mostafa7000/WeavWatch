<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Rules\UniqueCodeInBatch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DressesRelationManager extends RelationManager
{
    protected static string $relationship = 'dresses';
    protected static ?string $recordTitleAttribute = 'code';
    protected static ?string $modelLabel = 'فستان';
    protected static ?string $pluralModelLabel = self::PLURAL_NAME;
    protected static ?string $title = self::PLURAL_NAME;
    private const PLURAL_NAME = 'الفساتين';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('الكود')
                    ->required()
                    ->maxLength(255)
                    ->rule(new UniqueCodeInBatch($this->ownerRecord->id)),
                Forms\Components\Select::make('color_id')
                    ->relationship('color', 'title')
                    ->required()
                    ->preload()
                    ->label('اللون'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->groups(
                ['color.title']
            )
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->label('الكود'),
                Tables\Columns\TextColumn::make('color.title')
                    ->sortable()
                    ->searchable()
                    ->label('اللون')
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
