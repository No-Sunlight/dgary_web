<?php

namespace App\Filament\Resources\StockCounts;

use App\Filament\Resources\StockCounts\Pages\CreateStockCount;
use App\Filament\Resources\StockCounts\Pages\EditStockCount;
use App\Filament\Resources\StockCounts\Pages\ListStockCounts;
use App\Filament\Resources\StockCounts\Schemas\StockCountForm;
use App\Filament\Resources\StockCounts\Tables\StockCountsTable;
use App\Models\StockCount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockCountResource extends Resource
{
    protected static ?string $model = StockCount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Control de inventario';
        protected static ?string $modelLabel = 'Control de inventario';


    public static function form(Schema $schema): Schema
    {
        return StockCountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockCountsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockCounts::route('/'),
            'create' => CreateStockCount::route('/create'),
            'edit' => EditStockCount::route('/{record}/edit'),
        ];
    }
}
