<?php

namespace App\Filament\Resources\BatchResource\RelationManagers;

use App\Models\Dress;
use App\Models\IronDefect;
use App\Models\OperationDefect;
use App\Models\OperationDefectReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OperationDefectsRelationManager extends RelationManager
{
    protected static string $relationship = 'operation_defects';
    protected static ?string $modelLabel = 'عيب';
    protected static ?string $pluralModelLabel = self::PLURAL_NAME;
    protected static ?string $title = self::PLURAL_NAME;
    private const PLURAL_NAME= 'عيوب التشغيل';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('الثوب - اللون')
                    ->description('اختر الثوب والمقاس و العيب المطلوبين')
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
                            ->live()
                            ->required(),
                        Forms\Components\Select::make('defect_id')
                            ->label('العيب')
                            ->options(function () {
                                return OperationDefect::all()->pluck('title', 'id')->toArray();
                            })
                            ->disableOptionWhen(function (string $value, Get $get) {
                                if (isset($this->cachedMountedTableActionRecord) && $this->cachedMountedTableActionRecord->defect_id == $value) {
                                    return false;
                                }
                                $entered = OperationDefectReport::query()
                                    ->where('batch_id', $this->ownerRecord->id)
                                    ->where('dress_id', $get('dress_id'))
                                    ->where('size_id', $get('size_id'))
                                    ->get()->pluck('defect_id')->toArray();
                                return in_array($value, $entered);
                            })
                            ->disabled(fn(Get $get) => empty($get('dress_id')) || empty($get('size_id')))
                    ])->columns(3),
                Forms\Components\Section::make('العيوب')
                    ->description('أدخل العيوب')
                    ->schema([
                        Forms\Components\TextInput::make('a1')
                            ->label('08:10')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),

                        Forms\Components\TextInput::make('a2')
                            ->label('09:10')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),

                        Forms\Components\TextInput::make('a3')
                            ->label('10:10')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),

                        Forms\Components\TextInput::make('a4')
                            ->label('11:10')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),

                        Forms\Components\TextInput::make('a5')
                            ->label('12:00')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),

                        Forms\Components\TextInput::make('a6')
                            ->label('01:30')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),

                        Forms\Components\TextInput::make('a7')
                            ->label('02:10')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),

                        Forms\Components\TextInput::make('a8')
                            ->label('03:10')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),

                        Forms\Components\TextInput::make('a9')
                            ->label('04:10')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),

                        Forms\Components\TextInput::make('a10')
                            ->label('05:10')
                            ->numeric()
                            ->rules('min:0')
                            ->default(0),


                    ])->columns(7),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('batch_id')
            ->groups(['dress.color.title','size.title'])
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
                Tables\Columns\TextColumn::make('defect.title')
                    ->sortable()
                    ->searchable()
                    ->label('العيب'),
                Tables\Columns\TextColumn::make('a1')->label('08:10')->sortable(),
                Tables\Columns\TextColumn::make('a2')->label('09:10')->sortable(),
                Tables\Columns\TextColumn::make('a3')->label('10:10')->sortable(),
                Tables\Columns\TextColumn::make('a4')->label('11:10')->sortable(),
                Tables\Columns\TextColumn::make('a5')->label('12:00')->sortable(),
                Tables\Columns\TextColumn::make('a6')->label('01:30')->sortable(),
                Tables\Columns\TextColumn::make('a7')->label('02:10')->sortable(),
                Tables\Columns\TextColumn::make('a8')->label('03:10')->sortable(),
                Tables\Columns\TextColumn::make('a9')->label('04:10')->sortable(),
                Tables\Columns\TextColumn::make('a10')->label('05:10')->sortable(),
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
