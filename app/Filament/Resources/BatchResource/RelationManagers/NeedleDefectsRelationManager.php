<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Dress;
use App\Models\NeedleDefect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NeedleDefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'needle_defects';

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

                                $builder = NeedleDefect::query()->where('batch_id', $this->ownerRecord->id)
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
                            ->label('تفويت غرزة'),
                        Forms\Components\TextInput::make('a2')
                            ->label('ظهور خيط المكوك على خيط الحرير'),
                        Forms\Components\TextInput::make('a3')
                            ->label('بقع زيت'),
                        Forms\Components\TextInput::make('a4')
                            ->label('ترحيل الرسمة'),
                        Forms\Components\TextInput::make('a5')
                            ->label('ترحيل الأبليك'),
                        Forms\Components\TextInput::make('a6')
                            ->label('تنسيل'),
                        Forms\Components\TextInput::make('a7')
                            ->label('عدم ضبط الشد'),
                        Forms\Components\TextInput::make('a8')
                            ->label('عدم ضبط ألوان الفيلم'),
                        Forms\Components\TextInput::make('a9')
                            ->label('كثافة الغرز'),
                        Forms\Components\TextInput::make('a10')
                            ->label('ترحيل في مكان التطريز'),
                        Forms\Components\TextInput::make('a11')
                            ->label('تشطيب أبليك سيء'),
                    ])->columns(5),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('dress.code')
                    ->label('كود الثوب')
                    ->color('info'),
                Tables\Columns\TextColumn::make('dress.color.title')
                    ->label('لون الثوب'),
                Tables\Columns\TextColumn::make('size.title')
                    ->label('المقاس'),
                Tables\Columns\TextColumn::make('a1')
                    ->label('تفويت غرزة'),
                Tables\Columns\TextColumn::make('a3')
                    ->label('بقع زيت'),
                Tables\Columns\TextColumn::make('a4')
                    ->label('ترحيل الرسمة'),
                Tables\Columns\TextColumn::make('a5')
                    ->label('ترحيل الأبليك'),
                Tables\Columns\TextColumn::make('a6')
                    ->label('تنسيل'),
                Tables\Columns\TextColumn::make('a7')
                    ->label('عدم ضبط الشد'),
                Tables\Columns\TextColumn::make('a8')
                    ->label('عدم ضبط ألوان الفيلم'),
                Tables\Columns\TextColumn::make('a9')
                    ->label('كثافة الغرز'),
                Tables\Columns\TextColumn::make('a10')
                    ->label('ترحيل في مكان التطريز'),
                Tables\Columns\TextColumn::make('a11')
                    ->label('تشطيب أبليك سيء'),
                Tables\Columns\TextColumn::make('a2')
                    ->label('ظهور خيط المكوك على خيط الحرير'),
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
