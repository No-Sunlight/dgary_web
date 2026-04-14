<?php

namespace App\Filament\Resources\Deliveries\Tables;

use App\Models\Delivery;
use App\Models\Driver;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DeliveriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->numeric()
                    ->label("Repartidor")
                    ->sortable(),
                TextColumn::make('address')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //EditAction::make(),
                ViewAction::make(),
                Action::make("assign_driver")
                    ->label('Asignar')
                    ->color('success')
                    ->icon(Heroicon::Truck)
                    ->modalHeading('Seleccione un repartidor para la orden')
                    ->modalDescription('Una vez asignado un repartidor el estatus será cambiado a "en camino"')

                    ->form([
                        Select::make("driver")
                            ->label('Repartidor')
                            ->required()
                            ->options(function (Model $record) {

                                $unavailabe_drivers = Delivery::where('status', 'in_transit')
                                    ->pluck('user_id')
                                    ->toArray();

                                if (empty($unavailabe_drivers)) {
                                    return Driver::all()->pluck('name', 'id');
                                }

                                $drivers = Driver::all();
                                $array = [];

                                if (!empty($record->user_id)) {
                                    $array[$record->user_id] = Driver::find($record->user_id)->name;
                                }

                                foreach ($drivers as $driver) {
                                    if (!in_array($driver->id, $unavailabe_drivers)) {
                                        $array[$driver->id] = $driver->name;
                                    }
                                }

                                return $array;
                            })
                    ])
<<<<<<< HEAD
=======
                    ->action(function($data,Model $record)
                        {                        
                        
                        $record->user_id=$data['driver'];
                        $record->save();
>>>>>>> 8380424617cc494c900dd21e1a6f793bb65246fc

                    ->action(function (Model $record, array $data) {

                        $record->update([
                            'user_id' => $data['driver'],
                            'status' => 'in_transit', // AQUÍ SE DISPARA EL OBSERVER
                        ]);

                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}