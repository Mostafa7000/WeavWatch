<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Dress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;


class ClothDefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'cloth_defects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب-اللون')
                    ->description('اختر الثوب المطلوب')
                    ->schema([
                        Forms\Components\Select::make('dress_id')
                            ->disableOptionWhen(function (string $value) {
                                $entered = $this->ownerRecord->cloth_defects;
                                if (isset($this->cachedMountedTableActionRecord)) {
                                    /** @var Collection $entered */
                                    $entered = $entered->where('id', '!=', $this->cachedMountedTableActionRecordKey);
                                }
                                $entered = $entered->flatMap(fn($defect)=>$defect->pluck('id'))->toArray();

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
                        Forms\Components\TextInput::make('a1')->label('الريجة')->numeric(),
                        Forms\Components\TextInput::make('a2')->label('البانشر')->numeric(),
                        Forms\Components\TextInput::make('a3')->label('العقدة')->numeric(),
                        Forms\Components\TextInput::make('a4')->label('الطيرة')->numeric(),
                        Forms\Components\TextInput::make('a5')->label('الثقوب')->numeric(),
                        Forms\Components\TextInput::make('a6')->label('تسقيط')->numeric(),
                        Forms\Components\TextInput::make('a7')->label('تنسيل')->numeric(),
                        Forms\Components\TextInput::make('a8')->label('ثبوت اللون')->numeric(),
                        Forms\Components\TextInput::make('a9')->label('الوصلات')->numeric(),
                        Forms\Components\TextInput::make('a10')->label('الاتساخ')->numeric(),
                        Forms\Components\TextInput::make('a11')->label('فلوك')->numeric(),
                        Forms\Components\TextInput::make('a12')->label('عرض البرسل')->numeric(),
                        Forms\Components\TextInput::make('a13')->label('الزيت')->numeric(),
                        Forms\Components\TextInput::make('a14')->label('التيك الأسود')->numeric(),
                        Forms\Components\TextInput::make('a15')->label('عروض مختلفة')->numeric(),
                        Forms\Components\TextInput::make('a16')->label('الصدأ')->numeric(),
                        Forms\Components\TextInput::make('a17')->label('تنميل الصبغة')->numeric(),
                        Forms\Components\TextInput::make('a18')->label('اختلاف اللون العرضي')->numeric(),
                        Forms\Components\TextInput::make('a19')->label('اختلاف اللون الطولي')->numeric(),
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
                    ->label('الريجة'),
                Tables\Columns\TextColumn::make('a2')
                    ->label('البانشر'),
                Tables\Columns\TextColumn::make('a3')
                    ->label('العقدة'),
                Tables\Columns\TextColumn::make('a4')
                    ->label('الطيرة'),
                Tables\Columns\TextColumn::make('a5')
                    ->label('الثقوب'),
                Tables\Columns\TextColumn::make('a6')
                    ->label('تسقيط'),
                Tables\Columns\TextColumn::make('a7')
                    ->label('تنسيل'),
                Tables\Columns\TextColumn::make('a8')
                    ->label('ثبوت اللون'),
                Tables\Columns\TextColumn::make('a9')
                    ->label('الوصلات'),
                Tables\Columns\TextColumn::make('a10')
                    ->label('الاتساخ'),
                Tables\Columns\TextColumn::make('a11')
                    ->label('فلوك'),
                Tables\Columns\TextColumn::make('a12')
                    ->label('عرض البرسل'),
                Tables\Columns\TextColumn::make('a13')
                    ->label('الزيت'),
                Tables\Columns\TextColumn::make('a14')
                    ->label('التيك الأسود'),
                Tables\Columns\TextColumn::make('a15')
                    ->label('عروض مختلفة'),
                Tables\Columns\TextColumn::make('a16')
                    ->label('الصدأ'),
                Tables\Columns\TextColumn::make('a17')
                    ->label('تنميل الصبغة'),
                Tables\Columns\TextColumn::make('a18')
                    ->label('اختلاف اللون العرضي'),
                Tables\Columns\TextColumn::make('a19')
                    ->label('اختلاف اللون الطولي'),

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