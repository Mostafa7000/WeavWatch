<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\CuttingDefect;
use App\Models\Dress;
use App\Models\NeedleDefect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CuttingDefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'cutting_defects';
    protected static ?string $modelLabel = 'عيب';
    protected static ?string $pluralModelLabel = self::PLURAL_NAME;
    protected static ?string $title = self::PLURAL_NAME;
    private const PLURAL_NAME = 'عيوب القص';

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

                                $builder = CuttingDefect::query()->where('batch_id', $this->ownerRecord->id)
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
                            ->label('شرشرة')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a2')
                            ->label('عدم تماثل')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a3')
                            ->label('اعوجاج في شكل القص')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a4')
                            ->label('قطع تالفة')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a5')
                            ->label('عيب في البرسل')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a6')
                            ->label('فورت غير موجود')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a7')
                            ->label('فورت عميق')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a8')
                            ->label('نقص طول أو عرض')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a9')
                            ->label('قطع هربانة')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a10')
                            ->label('قص غير جيد')
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
                    ->label('اللون'),
                Tables\Columns\TextColumn::make('size.title')
                    ->sortable()
                    ->searchable()
                    ->label('المقاس'),
                Tables\Columns\TextColumn::make('a1')
                    ->label('شرشرة')->sortable(),
                Tables\Columns\TextColumn::make('a2')
                    ->label('عدم تماثل')->sortable(),
                Tables\Columns\TextColumn::make('a3')
                    ->label('اعوجاج في شكل القص')->sortable(),
                Tables\Columns\TextColumn::make('a4')
                    ->label('قطع تالفة')->sortable(),
                Tables\Columns\TextColumn::make('a5')
                    ->label('عيب في البرسل')->sortable(),
                Tables\Columns\TextColumn::make('a6')
                    ->label('فورت غير موجود')->sortable(),
                Tables\Columns\TextColumn::make('a7')
                    ->label('فورت عميق')->sortable(),
                Tables\Columns\TextColumn::make('a8')
                    ->label('نقص طول أو عرض')->sortable(),
                Tables\Columns\TextColumn::make('a9')
                    ->label('قطع هربانة')->sortable(),
                Tables\Columns\TextColumn::make('a10')
                    ->label('قص غير جيد')->sortable(),
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
