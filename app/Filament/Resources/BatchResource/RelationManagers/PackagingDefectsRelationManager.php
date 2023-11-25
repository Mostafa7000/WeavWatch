<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Dress;
use App\Models\PackagingDefect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PackagingDefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'packaging_defects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب - اللون')
                    ->description('اختر الثوب والمقاس المطلوبين')
                    ->schema([
                        Forms\Components\Select::make('dress_id')
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
                            ->options(function () {
                                return $this->ownerRecord->sizes->pluck('title', 'id')->toArray();
                            })
                            ->disableOptionWhen(function (string $value, string $operation, Get $get) {
                                if (isset($this->cachedMountedTableActionRecord) && $this->cachedMountedTableActionRecord->size_id == $value) {
                                    return false;
                                }

                                $builder = PackagingDefect::query()->where('batch_id', $this->ownerRecord->id)
                                    ->where('dress_id', $get('dress_id'));
                                $entered = $builder->get()->pluck('size_id')->toArray();

                                return in_array($value, $entered);
                            })
                            ->disabled(fn(Get $get) => empty($get('dress_id')))
                            ->required()
                    ])->columns(2),
                Forms\Components\Section::make('العيوب')
                    ->description('أدخل العيوب')
                    ->schema([
                        Forms\Components\TextInput::make('a1')
                            ->label('مظهرية سيئة'),
                        Forms\Components\TextInput::make('a2')
                            ->label('عدم وجود نكت عناية'),
                        Forms\Components\TextInput::make('a3')
                            ->label('عدم وجود نكت مقاس'),
                        Forms\Components\TextInput::make('a4')
                            ->label('عدم وجود نكت أساسي عميل'),
                        Forms\Components\TextInput::make('a5')
                            ->label('نسبة رشيو خطأ'),
                        Forms\Components\TextInput::make('a6')
                            ->label('زيادة أو نقص في العدد الكلي للمقاس'),
                        Forms\Components\TextInput::make('a7')
                            ->label('مكواه سيئة'),
                        Forms\Components\TextInput::make('a8')
                            ->label('تطبيق سيئ'),
                        Forms\Components\TextInput::make('a9')
                            ->label('اكسسوار مفقود'),
                        Forms\Components\TextInput::make('a10')
                            ->label('دبوس ناقص'),
                        Forms\Components\TextInput::make('a11')
                            ->label('بقعة زيت أو عرق أو اتساخ'),
                        Forms\Components\TextInput::make('a12')
                            ->label('قطع مبللة داخل الكيس'),
                        Forms\Components\TextInput::make('a13')
                            ->label('زيادة او نقص فى العدد الكلى للون'),
                        Forms\Components\TextInput::make('a14')
                            ->label('تعبئة خطأ'),
                        Forms\Components\TextInput::make('a15')
                            ->label('كارت علامة تجارية خطأ'),
                        Forms\Components\TextInput::make('a16')
                            ->label('كارت سعر خطأ'),
                        Forms\Components\TextInput::make('a17')
                            ->label('عدم توافق المقاسات'),
                        Forms\Components\TextInput::make('a18')
                            ->label('شماعة خطأ'),
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
                Tables\Columns\TextColumn::make('size.title')
                    ->label('المقاس'),
                Tables\Columns\TextColumn::make('a1')
                    ->label('مظهرية سيئة'),
                Tables\Columns\TextColumn::make('a2')
                    ->label('عدم وجود نكت عناية'),
                Tables\Columns\TextColumn::make('a3')
                    ->label('عدم وجود نكت مقاس'),
                Tables\Columns\TextColumn::make('a4')
                    ->label('عدم وجود نكت أساسي عميل'),
                Tables\Columns\TextColumn::make('a5')
                    ->label('نسبة رشيو خطأ'),
                Tables\Columns\TextColumn::make('a6')
                    ->label('زيادة أو نقص في العدد الكلي للمقاس'),
                Tables\Columns\TextColumn::make('a7')
                    ->label('مكواه سيئة'),
                Tables\Columns\TextColumn::make('a8')
                    ->label('تطبيق سيئ'),
                Tables\Columns\TextColumn::make('a9')
                    ->label('اكسسوار مفقود'),
                Tables\Columns\TextColumn::make('a10')
                    ->label('دبوس ناقص'),
                Tables\Columns\TextColumn::make('a11')
                    ->label('بقعة زيت أو عرق أو اتساخ'),
                Tables\Columns\TextColumn::make('a12')
                    ->label('قطع مبللة داخل الكيس'),
                Tables\Columns\TextColumn::make('a13')
                    ->label('زيادة او نقص فى العدد الكلى للون'),
                Tables\Columns\TextColumn::make('a14')
                    ->label('تعبئة خطأ'),
                Tables\Columns\TextColumn::make('a15')
                    ->label('كارت علامة تجارية خطأ'),
                Tables\Columns\TextColumn::make('a16')
                    ->label('كارت سعر خطأ'),
                Tables\Columns\TextColumn::make('a17')
                    ->label('عدم توافق المقاسات'),
                Tables\Columns\TextColumn::make('a18')
                    ->label('شماعة خطأ'),
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
