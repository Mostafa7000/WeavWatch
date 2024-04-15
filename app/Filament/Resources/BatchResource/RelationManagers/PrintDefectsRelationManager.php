<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Dress;
use App\Models\NeedleDefect;
use App\Models\PrintDefect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PrintDefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'print_defects';
    protected static ?string $modelLabel = 'عيب';
    protected static ?string $pluralModelLabel = self::PLURAL_NAME;
    protected static ?string $title = self::PLURAL_NAME;
    private const PLURAL_NAME = 'عيوب الطباعة';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب-اللون')
                    ->description('اختر الثوب المطلوب')
                    ->schema([
                        Forms\Components\Select::make('dress_id')
                            ->label('الثوب')
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
                            ->required()
                            ->live(true),
                        Forms\Components\Select::make('size_id')
                            ->label('المقاس')
                            ->options(function () {
                                return $this->ownerRecord->sizes->pluck('title', 'id')->toArray();
                            })
                            ->disableOptionWhen(function (string $value, string $operation, Get $get) {
                                if (isset($this->cachedMountedTableActionRecord) && $this->cachedMountedTableActionRecord->size_id == $value) {
                                    return false;
                                }

                                $builder = PrintDefect::query()->where('batch_id', $this->ownerRecord->id)
                                    ->where('dress_id', $get('dress_id'));
                                $entered = $builder->get()->pluck('size_id')->toArray();

                                return in_array($value, $entered);
                            })
                            ->disabled(fn(Get $get) => empty($get('dress_id')))
                            ->required()
                    ])->columns(1),
                Forms\Components\Section::make('العيوب')
                    ->description('أدخل العيوب')
                    ->schema([
                        Forms\Components\TextInput::make('a1')
                            ->label('البقع')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a2')
                            ->label('علامات داكنة أو فاتحة')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a3')
                            ->label('ظهور نقط او خطوط')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a4')
                            ->label('الألوان فاتحة جدًا')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a5')
                            ->label('الالوان  داكنة جدًا')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a6')
                            ->label('أسطر بيضاء رأسية')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a7')
                            ->label('خطوط')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a8')
                            ->label('مسحوق الحبر مفقود')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a9')
                            ->label('مسحوق الحبر تسهل إزالته بالفرك')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a10')
                            ->label('الوان غير صحيحه')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a11')
                            ->label('طباعه باهته')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a12')
                            ->label('طباعه ملطخه')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a13')
                            ->label('طباعه مشققه')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a14')
                            ->label('اتساخات')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
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
                Tables\Columns\TextColumn::make('dress.color.title')
                    ->sortable()
                    ->searchable()
                    ->label('لون الثوب'),
                Tables\Columns\TextColumn::make('size.title')
                    ->sortable()
                    ->searchable()
                    ->label('المقاس'),
                Tables\Columns\TextColumn::make('a1')
                    ->sortable()
                    ->searchable()
                    ->label('البقع'),
                Tables\Columns\TextColumn::make('a2')
                    ->sortable()
                    ->searchable()
                    ->label('علامات داكنة أو فاتحة'),
                Tables\Columns\TextColumn::make('a3')
                    ->sortable()
                    ->searchable()
                    ->label('ظهور نقط او خطوط'),
                Tables\Columns\TextColumn::make('a4')
                    ->sortable()
                    ->searchable()
                    ->label('الألوان فاتحة جدًا'),
                Tables\Columns\TextColumn::make('a5')
                    ->sortable()
                    ->searchable()
                    ->label('الالوان  داكنة جدًا'),
                Tables\Columns\TextColumn::make('a6')
                    ->sortable()
                    ->searchable()
                    ->label('أسطر بيضاء رأسية'),
                Tables\Columns\TextColumn::make('a7')
                    ->sortable()
                    ->searchable()
                    ->label('خطوط'),
                Tables\Columns\TextColumn::make('a8')
                    ->sortable()
                    ->searchable()
                    ->label('مسحوق الحبر مفقود'),
                Tables\Columns\TextColumn::make('a9')
                    ->sortable()
                    ->searchable()
                    ->label('مسحوق الحبر تسهل إزالته بالفرك'),
                Tables\Columns\TextColumn::make('a10')
                    ->sortable()
                    ->searchable()
                    ->label('الوان غير صحيحه'),
                Tables\Columns\TextColumn::make('a11')
                    ->sortable()
                    ->searchable()
                    ->label('طباعه باهته'),
                Tables\Columns\TextColumn::make('a12')
                    ->sortable()
                    ->searchable()
                    ->label('طباعه ملطخه'),
                Tables\Columns\TextColumn::make('a13')
                    ->sortable()
                    ->searchable()
                    ->label('طباعه مشققه'),
                Tables\Columns\TextColumn::make('a14')
                    ->sortable()
                    ->searchable()
                    ->label('اتساخات'),
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
}
