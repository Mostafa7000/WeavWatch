<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Dress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CuttingDefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'cutting_defects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب-اللون')
                    ->description('اختر الثوب المطلوب')
                    ->schema([
                        Forms\Components\Select::make('dress_id')
                            ->disableOptionWhen(function (string $value) {
                                if (isset($this->cachedMountedTableActionRecord) && $this->cachedMountedTableActionRecord->dress_id == $value) {
                                    return false;
                                }

                                $entered = $this->ownerRecord->cutting_defects;
                                $entered = $entered->flatMap(fn($defect) => $defect->pluck('id'))->toArray();

                                return in_array($value, $entered);
                            })
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
                            ->unique(ignoreRecord: true)
                            ->required(),
                    ])->columns(1),
                Forms\Components\Section::make('العيوب')
                    ->description('أدخل العيوب')
                    ->schema([
                        Forms\Components\TextInput::make('a1')
                            ->label('شرشرة'),
                        Forms\Components\TextInput::make('a2')
                            ->label('عدم تماثل'),
                        Forms\Components\TextInput::make('a3')
                            ->label('اعوجاج في شكل القص'),
                        Forms\Components\TextInput::make('a4')
                            ->label('قطع تالفة'),
                        Forms\Components\TextInput::make('a5')
                            ->label('عيب في البرسل'),
                        Forms\Components\TextInput::make('a6')
                            ->label('فورت غير موجود'),
                        Forms\Components\TextInput::make('a7')
                            ->label('فورت عميق'),
                        Forms\Components\TextInput::make('a8')
                            ->label('نقص طول أو عرض'),
                        Forms\Components\TextInput::make('a9')
                            ->label('قطع هربانة'),
                        Forms\Components\TextInput::make('a10')
                            ->label('قص غير جيد'),
                    ])->columns(5),
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
                Tables\Columns\TextColumn::make('a1')
                    ->label('شرشرة'),
                Tables\Columns\TextColumn::make('a2')
                    ->label('عدم تماثل'),
                Tables\Columns\TextColumn::make('a3')
                    ->label('اعوجاج في شكل القص'),
                Tables\Columns\TextColumn::make('a4')
                    ->label('قطع تالفة'),
                Tables\Columns\TextColumn::make('a5')
                    ->label('عيب في البرسل'),
                Tables\Columns\TextColumn::make('a6')
                    ->label('فورت غير موجود'),
                Tables\Columns\TextColumn::make('a7')
                    ->label('فورت عميق'),
                Tables\Columns\TextColumn::make('a8')
                    ->label('نقص طول أو عرض'),
                Tables\Columns\TextColumn::make('a9')
                    ->label('قطع هربانة'),
                Tables\Columns\TextColumn::make('a10')
                    ->label('قص غير جيد'),
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
