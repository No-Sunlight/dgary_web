<?php

namespace App\Filament\Resources\Deliveries\Tables;

use App\Models\Delivery;
use App\Models\Driver;
use BladeUI\Icons\Components\Icon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class DeliveriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('driver.name')
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
                    ->label("Creada")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'pending',
                        'ready' => 'ready',
                        'in_transit' => 'in_transit',
                        'completed' => 'completed'
                    ]),


            ])
            ->recordActions([

                ViewAction::make()

                ,

                //Cancelar
                Action::make('cancel')
                    ->label('Cancelar')
                    ->modalHeading('Cancelar orden')
                    ->color('danger')
                    ->icon(Heroicon::ExclamationTriangle)
                    ->modalDescription('ADVERTENCIA: UNA VEZ CANCELADA LA ORDEN, NO PUEDE SER CAMBIADA')
                    ->action(function ($data, Model $record) {
                        $record->status = 'canceled';
                        $record->save();
                    }),



                //Indicar que la orden esta lista
                Action::make('pick-up_ready')
                    ->icon(Heroicon::Bell)
                    ->label('Lista')
                    ->visible(fn($record) => $record->status == 'pending')
                    ->modalHeading('Cambiar el estatus de la orden a "Lista"')
                    ->modalDescription('Solo cambie el estado si la orden esta lista para ser recogida por el repartidor')
                    ->action(function ($data, Model $record) {
                        $record->status = 'ready';
                        $record->save();
                    }),
                //Accion de asignar un repartidor.
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
