<?php

namespace App\Filament\Resources\StockCounts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockCountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                TextInput::make('count')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('last_counted_at'),
                Select::make('status')
                    ->options([
            'uncounted' => 'Uncounted',
            'matched' => 'Matched',
            'discrepancy' => 'Discrepancy',
            'stale' => 'Stale',
        ])
                    ->default('uncounted')
                    ->required(),
            ]);
    }
}
