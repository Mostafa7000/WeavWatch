<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Batch;
use App\Models\Dress;
use App\Models\Piece;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PiecesRelationManager extends RelationManager
{
    protected static string $relationship = 'pieces';
    protected static ?string $modelLabel = 'قطعة';
    protected static ?string $pluralModelLabel = self::PLURAL_NAME;
    protected static ?string $title = self::PLURAL_NAME;
    private const PLURAL_NAME= 'القطع';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('dress_id')
                    ->label("الثوب")
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
                    ->label('اللون')
                    ->preload()
                    ->live()
                    ->required()
                    ->afterStateUpdated(fn(Set $set) => $set('size_id', null)),
                Forms\Components\Select::make('size_id')
                    ->options(
                        function () {
                            return $this->ownerRecord->sizes->pluck('title', 'id')->toArray();
                        }
                    )
                    ->disableOptionWhen(function (string $value, Get $get) {
                        /** @var Collection $dresses */
                        $dresses = Dress::query()->where('id', $get('dress_id'))->get();
                        if (isset($this->cachedMountedTableActionRecord)) {
                            $dresses = $dresses
                                ->where('id', '!=', $this->cachedMountedTableActionRecord->dress_id);
                        }

                        $sizeIds = $dresses->flatMap(function ($dress) {
                            return $dress->sizes->pluck('id');
                        })->all();

                        if (in_array($value, $sizeIds)) {
                            return true;
                        } else {
                            return false;
                        }
                    })
                    ->label('المقاس')
                    ->live()
                    ->required()
                    ->disabled(fn(Get $get) => empty($get('dress_id'))),
                Forms\Components\TextInput::make('value')
                    ->label('العدد')
                    ->numeric()
                    ->hint('العدد المطلوب المتبقي = ' . $this->getRemainingPieces())
                    ->maxValue($this->getRemainingPieces())
                    ->required()
                    ->live(),
            ])->columns(3);
    }

    private function getRemainingPieces()
    {
        $required = Batch::query()
            ->where('id', $this->ownerRecord->id)
            ->get()
            ->sum('required_quantity');
        $made = Piece::query()
            ->where('batch_id', $this->ownerRecord->id)
            ->get()
            ->sum('value');
        return ($required - $made) > 0 ? $required - $made : 0;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dress.color.title')
                    ->sortable()
                    ->searchable()
                    ->label('اللون'),
                Tables\Columns\TextColumn::make('size.title')
                    ->sortable()
                    ->searchable()
                    ->label('المقاس'),
                Tables\Columns\TextColumn::make('value')
                    ->sortable()
                    ->searchable()
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
