<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Dress;
use App\Models\IronDefect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class IronDefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'iron_defects';
    protected static ?string $modelLabel = 'عيب';
    protected static ?string $pluralModelLabel = self::PLURAL_NAME;
    protected static ?string $title = self::PLURAL_NAME;
    private const PLURAL_NAME= 'عيوب الكي';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب - اللون')
                    ->description('اختر الثوب والمقاس المطلوبين')
                    ->schema([
                        Forms\Components\Select::make('dress_id')
                            ->label('الثوب')
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
                            ->label('المقاس')
                            ->options(function () {
                                return $this->ownerRecord->sizes->pluck('title', 'id')->toArray();
                            })
                            ->disableOptionWhen(function (string $value, string $operation, Get $get) {
                                if (isset($this->cachedMountedTableActionRecord) && $this->cachedMountedTableActionRecord->size_id == $value) {
                                    return false;
                                }

                                $builder = IronDefect::query()->where('batch_id', $this->ownerRecord->id)
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
                            ->label('اتساخ')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a2')
                            ->label('لسعة')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a3')
                            ->label('حرق')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a4')
                            ->label('كرمشة')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a5')
                            ->label('بقع معدنية')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a6')
                            ->label('لمعان')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a7')
                            ->label('تكسير')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a8')
                            ->label('بخار زيادة')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a9')
                            ->label('بلل')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a10')
                            ->label('عدم ضبط المظهرية')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a11')
                            ->label('علامات ضغط')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),
                        Forms\Components\TextInput::make('a12')
                            ->label('انكماش')
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
            ->groups(
                ['size.title']
            )
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
                Tables\Columns\TextColumn::make('size.title')
                    ->sortable()
                    ->searchable()
                    ->label('المقاس'),
                Tables\Columns\TextColumn::make('a1')
                    ->label('اتساخ')->sortable(),
                Tables\Columns\TextColumn::make('a2')
                    ->label('لسعة')->sortable(),
                Tables\Columns\TextColumn::make('a3')
                    ->label('حرق')->sortable(),
                Tables\Columns\TextColumn::make('a4')
                    ->label('كرمشة')->sortable(),
                Tables\Columns\TextColumn::make('a5')
                    ->label('بقع معدنية')->sortable(),
                Tables\Columns\TextColumn::make('a6')
                    ->label('لمعان')->sortable(),
                Tables\Columns\TextColumn::make('a7')
                    ->label('تكسير')->sortable(),
                Tables\Columns\TextColumn::make('a8')
                    ->label('بخار زيادة')->sortable(),
                Tables\Columns\TextColumn::make('a9')
                    ->label('بلل')->sortable(),
                Tables\Columns\TextColumn::make('a10')
                    ->label('عدم ضبط المظهرية')->sortable(),
                Tables\Columns\TextColumn::make('a11')
                    ->label('علامات ضغط')->sortable(),
                Tables\Columns\TextColumn::make('a12')
                    ->label('انكماش')->sortable(),
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
