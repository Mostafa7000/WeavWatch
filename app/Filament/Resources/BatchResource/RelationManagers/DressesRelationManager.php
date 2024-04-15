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
    protected static ?string $modelLabel = 'ثوب';
    protected static ?string $pluralModelLabel = self::PLURAL_NAME;
    protected static ?string $title = self::PLURAL_NAME;
    private const PLURAL_NAME = 'الأثواب';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('كود الثوب')
                    ->required()
                    ->maxLength(255)
                    ->rule(new UniqueCodeInBatch($this->ownerRecord->id)),
                Forms\Components\Select::make('color_id')
                    ->relationship('color', 'title')
                    ->required()
                    ->preload()
                    ->label('اللون'),
                Forms\Components\TextInput::make('bath_code')
                    ->label('كود الحوض')
                    ->maxLength(255),
                Forms\Components\TextInput::make('meters')
                    ->label('عدد الأمتار')
                    ->maxLength(255)
            ])->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->groups(
                ['color.title']
            )
            ->recordTitleAttribute('code')
            ->columns([
                Tables\Columns\TextColumn::make('bath_code')
                    ->sortable()
                    ->searchable()
                    ->label('كود الحوض'),
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->label('كود الثوب'),
                Tables\Columns\TextColumn::make('color.title')
                    ->sortable()
                    ->searchable()
                    ->label('اللون'),
                Tables\Columns\TextColumn::make('meters')
                    ->sortable()
                    ->searchable()
                    ->label('عدد الأمتار')
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
