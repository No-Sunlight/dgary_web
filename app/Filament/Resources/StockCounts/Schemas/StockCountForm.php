<?php

namespace App\Filament\Resources\StockCounts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StockCountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product_id')
                    ->formatStateUsing(fn ($record) => $record?->product?->name)
                    ->label("Nombre de producto"),
                    
                TextEntry::make('Stock Digital:')
                    ->state(fn ($record) => $record?->product?->stock),
                TextEntry::make('Estatus') 
                ->state(fn($record)=>$record?->status),       
                
                    TextInput::make('count')
                    ->label("Existencias fisicas")
                    ->required()
                    ->numeric()
                    ->default(0),

             //   DateTimePicker::make('last_counted_at'),
        //         Select::make('status')
        //             ->options([
        //     'uncounted' => 'Uncounted',
        //     'matched' => 'Matched',
        //     'discrepancy' => 'Discrepancy',
        //     'stale' => 'Stale',
        // ])
        //             ->default('uncounted')
                  //  ->required(),
            ]);
    }
}
