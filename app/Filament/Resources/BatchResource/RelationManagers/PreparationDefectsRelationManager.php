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
                            ->label('dress')
                            ->disableOptionWhen(function (string $value) {
                                $entered = $this->ownerRecord->preparation_defects;
                                if (isset($this->cachedMountedTableActionRecord)) {
                                    /** @var Collection $entered */
                                    $entered = $entered->where('id', '!=', $this->cachedMountedTableActionRecordKey);
                                }
                                $entered = $entered->flatMap(fn($defect) => $defect->pluck('dress_id'))->toArray();

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
                            ->label('خطأ نمرة رفيعة')->default(0),
                        Forms\Components\TextInput::make('a2')
                            ->label('خطأ نمرة سميكة')->default(0),
                        Forms\Components\TextInput::make('a3')
                            ->label('فتلة مخلوطة')->default(0),
                        Forms\Components\TextInput::make('a4')
                            ->label('فتلة محلولة')->default(0),
                        Forms\Components\TextInput::make('a5')
                            ->label('عقد تراجي')->default(0),
                        Forms\Components\TextInput::make('a6')
                            ->label('نقط سواء في الفتل')->default(0),
                        Forms\Components\TextInput::make('a7')
                            ->label('نسبة الخلط غير منتظمة')->default(0),
                        Forms\Components\TextInput::make('a8')
                            ->label('لحمة متباعدة')->default(0),
                        Forms\Components\TextInput::make('a9')
                            ->label('حدفات غير منتظمة المسافات بين الخطوط')->default(0),
                        Forms\Components\TextInput::make('a10')
                            ->label('فراغ خال من اللحمات')->default(0),
                        Forms\Components\TextInput::make('a11')
                            ->label('دقات')->default(0),
                        Forms\Components\TextInput::make('a12')
                            ->label('اختلاف لحمة')->default(0),
                        Forms\Components\TextInput::make('a13')
                            ->label('ثقوب')->default(0),
                        Forms\Components\TextInput::make('a14')
                            ->label('عقد لحمة')->default(0),
                        Forms\Components\TextInput::make('a15')
                            ->label('قطع وصل')->default(0),
                        Forms\Components\TextInput::make('a16')
                            ->label('لحمة مقوسة')->default(0),
                        Forms\Components\TextInput::make('a17')
                            ->label('لحمة ليست على استقامة واحدة في طريق البرسل')->default(0),
                        Forms\Components\TextInput::make('a18')
                            ->label('اختلاف الشد على الخيوط')->default(0),
                        Forms\Components\TextInput::make('a19')
                            ->label('فتل زائدة')->default(0),
                        Forms\Components\TextInput::make('a20')
                            ->label('خطأ لقي')->default(0),
                        Forms\Components\TextInput::make('a21')
                            ->label('خطأ تطريح')->default(0),
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
