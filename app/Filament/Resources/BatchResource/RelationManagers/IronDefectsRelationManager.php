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
    protected $__id = '13';
    protected static string $relationship = 'iron_defects';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب - اللون')
                    ->description('اختر الثوب والمقاس المطلوبين')
                    ->schema([
                        Forms\Components\Select::make('dress_id')
                            ->label('Dress')
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
                            ->label('Size')
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
                    ->label('اتساخ'),
                Tables\Columns\TextColumn::make('a2')
                    ->label('لسعة'),
                Tables\Columns\TextColumn::make('a3')
                    ->label('حرق'),
                Tables\Columns\TextColumn::make('a4')
                    ->label('كرمشة'),
                Tables\Columns\TextColumn::make('a5')
                    ->label('بقع معدنية'),
                Tables\Columns\TextColumn::make('a6')
                    ->label('لمعان'),
                Tables\Columns\TextColumn::make('a7')
                    ->label('تكسير'),
                Tables\Columns\TextColumn::make('a8')
                    ->label('بخار زيادة'),
                Tables\Columns\TextColumn::make('a9')
                    ->label('بلل'),
                Tables\Columns\TextColumn::make('a10')
                    ->label('عدم ضبط المظهرية'),
                Tables\Columns\TextColumn::make('a11')
                    ->label('علامات ضغط'),
                Tables\Columns\TextColumn::make('a12')
                    ->label('انكماش'),
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
