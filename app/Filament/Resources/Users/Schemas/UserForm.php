<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),

        Select::make('roles')
->relationship(
        name: 'roles',
        titleAttribute: 'name',
        modifyQueryUsing: fn (Builder $query) => $query->where('name','<>','super_admin')
        //Importante para que el gerente no cree un super_admin por accidente
        //Nota personal discutir con el grupo si mejor cambiarlo a DBA: Database Administrator
    )            
    
    ->preload()
        
            ->searchable(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
            ]);
    }
}
