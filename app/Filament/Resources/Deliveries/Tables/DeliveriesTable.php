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
                    'completed'=>'completed'
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
                     ->action(function($data, Model $record)
                    {
                        $record->status='canceled';
                        $record->save();  
                    }),



                //Indicar que la orden esta lista
                Action::make('pick-up_ready')
                    ->icon(Heroicon::Bell)
                    ->label('Lista')
                    ->visible(fn ($record) => $record->status=='pending')
                     ->modalHeading('Cambiar el estatus de la orden a "Lista"')
                    ->modalDescription('Solo cambie el estado si la orden esta lista para ser recogida por el repartidor')
                    ->action(function($data, Model $record)
                    {
                        $record->status='ready';
                        $record->save();  
                    }),
                //Accion de asignar un repartidor.
                Action::make("assign_driver")
                    ->label('Asignar')
                    ->color('success')
                    ->visible(fn ($record) => $record->status=='ready')
                    ->icon(Heroicon::Truck)
                    ->modalHeading('Seleccione un repartidor para la orden')
                     //->modalSubmitAction(true)

                    ->modalDescription('Una vez asignado un repartidor el estatus sera cambiado a "en camino"')
                    ->schema([
                        TextInput::make('status')
                        ->default('in_transit')
                        ->hidden(true),
                        Select::make("driver")
                        ->options(
                        function(Model $record)
                        {$unavailabe_drivers = Delivery::where('status','=','in_transit')->pluck('user_id')->toArray(); //Todos los repartidores ocupados
                        if(empty($unavailabe_drivers)){//No hay ordenes en transito asi que todos los drivers estan disponibles
                            return Driver::all()->pluck('name', 'id');//Pluck todos los repartidores. 
                        } else{
                                $drivers = Driver::all();
                                $array =array();
                        
                            //Estoy editando y quiero cambiar el repartidor, si da empty significa que estoy asignando por primera vez
                                if(!empty($record->user_id)){
                                $array[$record->user_id]=Driver::find($record->user_id)->name; //Hay un driver asignado, solo se agrega el array de opciones
                                }
                                foreach ($drivers as $driver){
                                        
                                    if (!in_array($driver->id, $unavailabe_drivers)){
                                        $array[$driver->id]=$driver->name;}}
                                    return $array;
                            }
                        })

                    ])
                    ->action(function($data,Model $record)
                        {                        
                        
                        $record->user_id=$data['driver'];
                        $record->status='in_transit';
                        $record->save();

                        }
                    
                    )




            
                    ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
