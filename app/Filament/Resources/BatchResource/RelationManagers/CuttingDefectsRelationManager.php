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
    protected static ?string $modelLabel = 'عيب';
    protected static ?string $pluralModelLabel = self::PLURAL_NAME;
    protected static ?string $title = self::PLURAL_NAME;
    private const PLURAL_NAME= 'عيوب القص';
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
