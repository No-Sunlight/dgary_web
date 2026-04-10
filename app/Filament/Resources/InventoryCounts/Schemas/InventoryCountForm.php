<?php

namespace App\Filament\Resources\InventoryCounts\Schemas;

use App\Models\Supply;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use App\Models\Product;

class InventoryCountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Tipo de inventario')
                    ->options([
                        'product' => 'Productos',
                        'supply' => 'Insumos',
                    ])
                    ->required()
                    ->disabled(fn($record) => $record !== null),
            ]);
    }
}
