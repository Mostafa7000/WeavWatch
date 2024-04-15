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
    protected static ?string $modelLabel = 'عيب';
    protected static ?string $pluralModelLabel = self::PLURAL_NAME;
    protected static ?string $title = self::PLURAL_NAME;
    private const PLURAL_NAME = 'عيوب القماش';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب-اللون')
                    ->description('اختر الثوب المطلوب')
                    ->schema([
                        Forms\Components\Select::make('dress_id')
                            ->label('الثوب')
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
                                            return [$dress->id => $dress->code."-".$dress->color->title];
                                        })
                                    ->toArray()
                            )
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->reactive(),
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
                        Forms\Components\TextInput::make('a20')->label('خطأ نمرة رفيعة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a21')->label('خطأ نمرة سميكة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a22')->label('فتلة مخلوطة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a23')->label('فتلة محلولة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a24')->label('عقد تراجي')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a25')->label('نقط سواء في الفتل')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a26')->label('نسبة الخلط غير منتظمة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a27')->label('لحمة متباعدة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a28')->label('حدفات غير منتظمة المسافات بين الخطوط')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a29')->label('فراغ خال من اللحمات')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a30')->label('دقات')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a31')->label('اختلاف لحمة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a32')->label('ثقوب')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a33')->label('عقد لحمة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a34')->label('قطع وصل')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a35')->label('لحمة مقوسة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a36')->label('لحمة ليست على استقامة واحدة في طريق البرسل')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a37')->label('اختلاف الشد على الخيوط')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a38')->label('فتل زائدة')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a39')->label('خطأ لقي')->numeric()->rules('min:0')->default(0),
                        Forms\Components\TextInput::make('a40')->label('خطأ تطريح')->numeric()->rules('min:0')->default(0),
                    ])->columns(5),
                Forms\Components\Section::make('أخرى')
                    ->schema([
                        Forms\Components\TextInput::make('other')->label('عيوب أخرى')
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('batch_id')
            ->columns([
                Tables\Columns\TextColumn::make('dress.bath_code')
                    ->sortable()
                    ->searchable()
                    ->label('كود الحوض')
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
                Tables\Columns\TextColumn::make('a20')
                    ->label('خطأ نمرة رفيعة')->sortable(),
                Tables\Columns\TextColumn::make('a21')
                    ->label('خطأ نمرة سميكة')->sortable(),
                Tables\Columns\TextColumn::make('a22')
                    ->label('فتلة مخلوطة')->sortable(),
                Tables\Columns\TextColumn::make('a23')
                    ->label('فتلة محلولة')->sortable(),
                Tables\Columns\TextColumn::make('a24')
                    ->label('عقد تراجي')->sortable(),
                Tables\Columns\TextColumn::make('a25')
                    ->label('نقط سواء في الفتل')->sortable(),
                Tables\Columns\TextColumn::make('a26')
                    ->label('نسبة الخلط غير منتظمة')->sortable(),
                Tables\Columns\TextColumn::make('a27')
                    ->label('لحمة متباعدة')->sortable(),
                Tables\Columns\TextColumn::make('a28')
                    ->label('حدفات غير منتظمة المسافات بين الخطوط')->sortable(),
                Tables\Columns\TextColumn::make('a29')
                    ->label('فراغ خال من اللحمات')->sortable(),
                Tables\Columns\TextColumn::make('a30')
                    ->label('دقات')->sortable(),
                Tables\Columns\TextColumn::make('a31')
                    ->label('اختلاف لحمة')->sortable(),
                Tables\Columns\TextColumn::make('a32')
                    ->label('ثقوب')->sortable(),
                Tables\Columns\TextColumn::make('a33')
                    ->label('عقد لحمة')->sortable(),
                Tables\Columns\TextColumn::make('a34')
                    ->label('قطع وصل')->sortable(),
                Tables\Columns\TextColumn::make('a35')
                    ->label('لحمة مقوسة')->sortable(),
                Tables\Columns\TextColumn::make('a36')
                    ->label('لحمة ليست على استقامة واحدة في طريق البرسل')->sortable(),
                Tables\Columns\TextColumn::make('a37')
                    ->label('اختلاف الشد على الخيوط')->sortable(),
                Tables\Columns\TextColumn::make('a38')
                    ->label('فتل زائدة')->sortable(),
                Tables\Columns\TextColumn::make('a39')
                    ->label('خطأ لقي')->sortable(),
                Tables\Columns\TextColumn::make('a40')
                    ->label('خطأ تطريح')->sortable(),
                Tables\Columns\TextColumn::make('other')
                    ->label('عيوب أخرى')
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

    public function hidden(): bool
    {
        return true; // This will hide the posts from the user's view page
    }
}