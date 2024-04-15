<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\BatchResource\RelationManagers\ClothDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\CuttingDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\DressesRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\IronDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\NeedleDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\OperationDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\PackagingDefectsRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\PiecesRelationManager;
use App\Filament\Resources\BatchResource\RelationManagers\PrintDefectsRelationManager;
use App\Filament\Resources\BatchResource\Widgets\ClothDefectsReport;
use App\Models\Batch;
use App\Models\Customer;
use App\Models\CuttingDefect;
use App\Models\PrintDefect;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\Concerns\HasRelationManagers;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BatchResource extends Resource
{
    use HasRelationManagers;

    protected static ?string $model = Batch::class;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $activeNavigationIcon = 'heroicon-s-archive-box';
    protected static ?string $modelLabel = 'طلبية';
    protected static ?string $pluralModelLabel = 'الطلبيات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('رقم الطلبية')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer')
                            ->label('رقم العميل')
                            ->required()
                            ->getOptionLabelFromRecordUsing(fn(Customer $customer
                            ) => $customer->code.'- '.$customer->name),
                        Forms\Components\TextInput::make('product_number')
                            ->label('رقم المنتج')
                            ->required(),
                        Forms\Components\TextInput::make('batch_number')
                            ->label('رقم الطلبية')
                            ->required()
                    ])->columns(3),
                Forms\Components\Section::make('رقم التشغيل')
                    ->schema([
                        Forms\Components\TextInput::make('location')
                            ->label('مكان الإنتاج')
                            ->required()
                    ])->columns(1),
                Forms\Components\Section::make('الكمية الإجمالية')
                    ->schema([
                        Repeater::make('sizeQuantity')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('size_id')
                                    ->relationship('size', 'title', function ($query) {
                                        $query->orderBy('id');
                                    })
                                    ->label('المقاس')
                                    ->preload()
                                    ->required()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                                Forms\Components\TextInput::make('quantity')
                                    ->required()
                                    ->label('الكمية')
                            ])
                            ->defaultItems(1)
                            ->label('')
                            ->columns(2)
                            ->addActionLabel('إضافة')
                        ,
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.code')
                    ->label('رقم العميل'),
                Tables\Columns\TextColumn::make('product_number')
                    ->label('رقم المنتج'),
                Tables\Columns\TextColumn::make('batch_number')
                    ->label('رقم الطلبية'),
                Tables\Columns\TextColumn::make('location')
                    ->label('مكان الإنتاج')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('عرض وتعديل'),
                Tables\Actions\ViewAction::make()->label('التقارير'),
                Tables\Actions\Action::make('pdf')
                    ->label('تحميل')
                    ->color('success')
                    ->icon('heroicon-s-arrow-down-on-square')
                    ->url(fn(Batch $record) => route('pdf', $record), true)
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

    public static function getRelations(): array
    {
        return [
            DressesRelationManager::class,
            PiecesRelationManager::class,
            ClothDefectsRelationManager::class,
            CuttingDefectsRelationManager::class,
            NeedleDefectsRelationManager::class,
            PrintDefectsRelationManager::class,
            OperationDefectsRelationManager::class,
            IronDefectsRelationManager::class,
            PackagingDefectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
            'view' => Pages\ViewBatch::route('/{record}'),
        ];
    }
}
