<?php

namespace App\Filament\Resources\InventoryCounts;

use App\Filament\Resources\InventoryCounts\Pages\CreateInventoryCount;
use App\Filament\Resources\InventoryCounts\Pages\EditInventoryCount;
use App\Filament\Resources\InventoryCounts\Pages\ListInventoryCounts;
use App\Filament\Resources\InventoryCounts\Schemas\InventoryCountForm;
use App\Filament\Resources\InventoryCounts\Tables\InventoryCountsTable;
use App\Filament\Resources\InventoryCounts\RelationManagers\ItemsRelationManager;
use App\Models\InventoryCount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventoryCountResource extends Resource
{
    protected static ?string $model = InventoryCount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Conteo de inventario';
    protected static ?string $modelLabel = 'Conteo de inventario';

    public static function form(Schema $schema): Schema
    {
        return InventoryCountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryCountsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventoryCounts::route('/'),
            'create' => CreateInventoryCount::route('/create'),
            'edit' => EditInventoryCount::route('/{record}/edit'),
        ];
    }
}
