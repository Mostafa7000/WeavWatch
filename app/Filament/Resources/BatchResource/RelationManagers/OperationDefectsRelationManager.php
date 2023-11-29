<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Dress;
use App\Models\IronDefect;
use App\Models\OperationDefect;
use App\Models\OperationDefectReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OperationDefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'operation_defects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب - اللون')
                    ->description('اختر الثوب والمقاس و العيب المطلوبين')
                    ->schema([
                        Forms\Components\Select::make('dress_id')
                            ->label('Dress')
                            ->options(
                                Dress::with('color')
                                    ->where('batch_id', $this->ownerRecord->id)
                                    ->get()
                                    ->mapWithKeys(
                                        function ($dress) {
                                            return [$dress->id => $dress->code . "-" . $dress->color->title];
                                        })
                                    ->toArray()
                            )
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('size_id')
                            ->label('Size')
                            ->options(function () {
                                return $this->ownerRecord->sizes->pluck('title', 'id')->toArray();
                            })
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('defect_id')
                            ->options(function () {
                                return OperationDefect::all()->pluck('title', 'id')->toArray();
                            })
                            ->disableOptionWhen(function (string $value, Get $get) {
                                if (isset($this->cachedMountedTableActionRecord) && $this->cachedMountedTableActionRecord->defect_id == $value) {
                                    return false;
                                }
                                $entered = OperationDefectReport::query()
                                    ->where('batch_id', $this->ownerRecord->id)
                                    ->where('dress_id', $get('dress_id'))
                                    ->where('size_id', $get('size_id'))
                                    ->get()->pluck('defect_id')->toArray();
                                return in_array($value, $entered);
                            })
                            ->disabled(fn(Get $get) => empty($get('dress_id')) || empty($get('size_id')))
                    ])->columns(3),
                Forms\Components\Section::make('العيوب')
                    ->description('أدخل العيوب')
                    ->schema([
                        Forms\Components\TextInput::make('08:10')->numeric()->default(0),
                        Forms\Components\TextInput::make('09:10')->numeric()->default(0),
                        Forms\Components\TextInput::make('10:10')->numeric()->default(0),
                        Forms\Components\TextInput::make('11:10')->numeric()->default(0),
                        Forms\Components\TextInput::make('12:00')->numeric()->default(0),
                        Forms\Components\TextInput::make('01:30')->numeric()->default(0),
                        Forms\Components\TextInput::make('02:10')->numeric()->default(0),
                        Forms\Components\TextInput::make('03:10')->numeric()->default(0),
                        Forms\Components\TextInput::make('04:10')->numeric()->default(0),
                        Forms\Components\TextInput::make('05:10')->numeric()->default(0),
                    ])->columns(7),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('batch_id')
            ->columns([
                Tables\Columns\TextColumn::make('dress.code')
                    ->label('كود الثوب')
                    ->color('info'),
                Tables\Columns\TextColumn::make('dress.color.title')
                    ->label('لون الثوب'),
                Tables\Columns\TextColumn::make('size.title')
                    ->label('المقاس'),
                Tables\Columns\TextColumn::make('defect.title')
                    ->label('العيب'),
                Tables\Columns\TextColumn::make('08:10'),
                Tables\Columns\TextColumn::make('09:10'),
                Tables\Columns\TextColumn::make('10:10'),
                Tables\Columns\TextColumn::make('11:10'),
                Tables\Columns\TextColumn::make('12:00'),
                Tables\Columns\TextColumn::make('01:30'),
                Tables\Columns\TextColumn::make('02:10'),
                Tables\Columns\TextColumn::make('03:10'),
                Tables\Columns\TextColumn::make('04:10'),
                Tables\Columns\TextColumn::make('05:10'),

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
