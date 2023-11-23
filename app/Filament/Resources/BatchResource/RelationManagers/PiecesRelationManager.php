<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Dress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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
                Forms\Components\Select::make('dress_id')
                    ->options(
                        Dress::with('color')
                            ->where('batch_id', $this->ownerRecord->id)
                            ->get()
                            ->mapWithKeys(
                                function ($dress) {
                                    return [$dress->id => $dress->color->title];
                                })
                            ->toArray()
                    )
                    ->label('Color')
                    ->preload()
                    ->live()
                    ->required()
                    ->afterStateUpdated(fn(Set $set) => $set('size_id', null)),
                Forms\Components\Select::make('size_id')
                    ->options(
                        function (Get $get, $operation) {
                            $sizesInBatch = $this->ownerRecord->sizes->pluck('title', 'id')->toArray();
                            if ($operation == 'create') {
                                $sizesInDresses = $this->ownerRecord->dresses
                                    ->flatMap(function ($dress) {
                                        return $dress->sizes->pluck('title', 'id');
                                    })->toArray();
                            } else {
                                $dressId = $get('dress_id');
                                $sizesInDresses = $this->ownerRecord->dresses
                                    ->where('id', '!=', $dressId)
                                    ->flatMap(function ($dress) {
                                        return $dress->sizes->pluck('title', 'id');
                                    })->toArray();
                            }
                            return array_diff($sizesInBatch, $sizesInDresses);
                        }
                    )
                    ->label('Size')
                    ->live()
                    ->required()
                    ->disabled(fn(Get $get) => empty($get('dress_id'))),
                Forms\Components\TextInput::make('value')
                    ->numeric()
                    ->required()
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dress.color.title')
                    ->label('اللون'),
                Tables\Columns\TextColumn::make('size.title')
                    ->label('المقاس'),
                Tables\Columns\TextColumn::make('value')
                    ->label('الكمية المطلوبة')

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
