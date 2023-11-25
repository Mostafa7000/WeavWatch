<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Dress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PreparationDefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'preparation_defects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب-اللون')
                    ->description('اختر الثوب المطلوب')
                    ->schema([
                        Forms\Components\Select::make('dress_id')
                            ->disableOptionWhen(function (string $value) {
                                $entered = $this->ownerRecord->preparation_defects;
                                if (isset($this->cachedMountedTableActionRecord)) {
                                    /** @var Collection $entered */
                                    $entered = $entered->where('id', '!=', $this->cachedMountedTableActionRecordKey);
                                }
                                $entered = $entered->flatMap(fn($defect) => $defect->pluck('id'))->toArray();

                                if (in_array($value, $entered)) {
                                    return true;
                                } else {
                                    return false;
                                }
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
                            ->label('خطأ نمرة رفيعة'),
                        Forms\Components\TextInput::make('a2')
                            ->label('خطأ نمرة سميكة'),
                        Forms\Components\TextInput::make('a3')
                            ->label('فتلة مخلوطة'),
                        Forms\Components\TextInput::make('a4')
                            ->label('فتلة محلولة'),
                        Forms\Components\TextInput::make('a5')
                            ->label('عقد تراجي'),
                        Forms\Components\TextInput::make('a6')
                            ->label('نقط سواء في الفتل'),
                        Forms\Components\TextInput::make('a7')
                            ->label('نسبة الخلط غير منتظمة'),
                        Forms\Components\TextInput::make('a8')
                            ->label('لحمة متباعدة'),
                        Forms\Components\TextInput::make('a9')
                            ->label('حدفات غير منتظمة المسافات بين الخطوط'),
                        Forms\Components\TextInput::make('a10')
                            ->label('فراغ خال من اللحمات'),
                        Forms\Components\TextInput::make('a11')
                            ->label('دقات'),
                        Forms\Components\TextInput::make('a12')
                            ->label('اختلاف لحمة'),
                        Forms\Components\TextInput::make('a13')
                            ->label('ثقوب'),
                        Forms\Components\TextInput::make('a14')
                            ->label('عقد لحمة'),
                        Forms\Components\TextInput::make('a15')
                            ->label('قطع وصل'),
                        Forms\Components\TextInput::make('a16')
                            ->label('لحمة مقوسة'),
                        Forms\Components\TextInput::make('a17')
                            ->label('لحمة ليست على استقامة واحدة في طريق البرسل'),
                        Forms\Components\TextInput::make('a18')
                            ->label('اختلاف الشد على الخيوط'),
                        Forms\Components\TextInput::make('a19')
                            ->label('فتل زائدة'),
                        Forms\Components\TextInput::make('a20')
                            ->label('خطأ لقي'),
                        Forms\Components\TextInput::make('a21')
                            ->label('خطأ تطريح'),
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
                    ->label('خطأ نمرة رفيعة'),
                Tables\Columns\TextColumn::make('a2')
                    ->label('خطأ نمرة سميكة'),
                Tables\Columns\TextColumn::make('a3')
                    ->label('فتلة مخلوطة'),
                Tables\Columns\TextColumn::make('a4')
                    ->label('فتلة محلولة'),
                Tables\Columns\TextColumn::make('a5')
                    ->label('عقد تراجي'),
                Tables\Columns\TextColumn::make('a6')
                    ->label('نقط سواء في الفتل'),
                Tables\Columns\TextColumn::make('a7')
                    ->label('نسبة الخلط غير منتظمة'),
                Tables\Columns\TextColumn::make('a8')
                    ->label('لحمة متباعدة'),
                Tables\Columns\TextColumn::make('a9')
                    ->label('حدفات غير منتظمة المسافات بين الخطوط'),
                Tables\Columns\TextColumn::make('a10')
                    ->label('فراغ خال من اللحمات'),
                Tables\Columns\TextColumn::make('a11')
                    ->label('دقات'),
                Tables\Columns\TextColumn::make('a12')
                    ->label('اختلاف لحمة'),
                Tables\Columns\TextColumn::make('a13')
                    ->label('ثقوب'),
                Tables\Columns\TextColumn::make('a14')
                    ->label('عقد لحمة'),
                Tables\Columns\TextColumn::make('a15')
                    ->label('قطع وصل'),
                Tables\Columns\TextColumn::make('a16')
                    ->label('لحمة مقوسة'),
                Tables\Columns\TextColumn::make('a17')
                    ->label('لحمة ليست على استقامة واحدة في طريق البرسل'),
                Tables\Columns\TextColumn::make('a18')
                    ->label('اختلاف الشد على الخيوط'),
                Tables\Columns\TextColumn::make('a19')
                    ->label('فتل زائدة'),
                Tables\Columns\TextColumn::make('a20')
                    ->label('خطأ لقي'),
                Tables\Columns\TextColumn::make('a21')
                    ->label('خطأ تطريح'),

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
