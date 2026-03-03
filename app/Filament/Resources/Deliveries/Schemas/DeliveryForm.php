<?php

namespace App\Filament\Resources\Deliveries\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DeliveryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('address')
                    ->required(),
                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'ready' => 'Ready',
            'completed' => 'Completed',
            'canceled' => 'Canceled',
        ])
                    ->default('completed')
                    ->required(),
                TextInput::make('total')
                    ->required()
                    ->numeric(),
            ]);
    }
}
