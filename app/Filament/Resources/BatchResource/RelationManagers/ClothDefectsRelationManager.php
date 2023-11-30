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
    protected $__id = '12';
    protected static string $relationship = 'cloth_defects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب-اللون')
                    ->description('اختر الثوب المطلوب')
                    ->schema([
                        Forms\Components\Select::make('dress_id')
                            ->label('Dress')
                            ->disableOptionWhen(function (string $value) {
                                $entered = $this->ownerRecord->cloth_defects;
                                if (isset($this->cachedMountedTableActionRecord)) {
                                    /** @var Collection $entered */
                                    $entered = $entered->where('id', '!=', $this->cachedMountedTableActionRecordKey);
                                }
                                $entered = $entered->flatMap(fn($defect) => $defect->pluck('dress_id'))->toArray();

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
                        Forms\Components\TextInput::make('a1')->label('الريجة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a2')->label('البانشر')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a3')->label('العقدة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a4')->label('الطيرة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a5')->label('الثقوب')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a6')->label('تسقيط')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a7')->label('تنسيل')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a8')->label('ثبوت اللون')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a9')->label('الوصلات')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a10')->label('الاتساخ')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a11')->label('فلوك')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a12')->label('عرض البرسل')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a13')->label('الزيت')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a14')->label('التيك الأسود')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a15')->label('عروض مختلفة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a16')->label('الصدأ')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a17')->label('تنميل الصبغة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a18')->label('اختلاف اللون العرضي')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a19')->label('اختلاف اللون الطولي')->numeric()->rules('min:0')->default(0),
                    ])->columns(5),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('batch_id')
            ->columns([
                Tables\Columns\TextColumn::make('dress.code')
                    ->sortable()
                    ->searchable()
                    ->label('كود الثوب')
                    ->color('info'),
                Tables\Columns\TextColumn::make('dress.color.title')
                    ->sortable()
                    ->searchable()
                    ->label('لون الثوب'),
                Tables\Columns\TextColumn::make('a1')
                    ->label('الريجة')->sortable(),
                Tables\Columns\TextColumn::make('a2')
                    ->label('البانشر')->sortable(),
                Tables\Columns\TextColumn::make('a3')
                    ->label('العقدة')->sortable(),
                Tables\Columns\TextColumn::make('a4')
                    ->label('الطيرة')->sortable(),
                Tables\Columns\TextColumn::make('a5')
                    ->label('الثقوب')->sortable(),
                Tables\Columns\TextColumn::make('a6')
                    ->label('تسقيط')->sortable(),
                Tables\Columns\TextColumn::make('a7')
                    ->label('تنسيل')->sortable(),
                Tables\Columns\TextColumn::make('a8')
                    ->label('ثبوت اللون')->sortable(),
                Tables\Columns\TextColumn::make('a9')
                    ->label('الوصلات')->sortable(),
                Tables\Columns\TextColumn::make('a10')
                    ->label('الاتساخ')->sortable(),
                Tables\Columns\TextColumn::make('a11')
                    ->label('فلوك')->sortable(),
                Tables\Columns\TextColumn::make('a12')
                    ->label('عرض البرسل')->sortable(),
                Tables\Columns\TextColumn::make('a13')
                    ->label('الزيت')->sortable(),
                Tables\Columns\TextColumn::make('a14')
                    ->label('التيك الأسود')->sortable(),
                Tables\Columns\TextColumn::make('a15')
                    ->label('عروض مختلفة')->sortable(),
                Tables\Columns\TextColumn::make('a16')
                    ->label('الصدأ')->sortable(),
                Tables\Columns\TextColumn::make('a17')
                    ->label('تنميل الصبغة')->sortable(),
                Tables\Columns\TextColumn::make('a18')
                    ->label('اختلاف اللون العرضي')->sortable(),
                Tables\Columns\TextColumn::make('a19')
                    ->label('اختلاف اللون الطولي')->sortable(),
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