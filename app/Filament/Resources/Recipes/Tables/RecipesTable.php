<?php

namespace App\Filament\Resources\Recipes\Tables;

use App\Models\PreparationLog;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\supply;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RecipesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Producto a crear')
                    ->sortable(),
                TextColumn::make('produced_quantity')
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
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                 Action::make('prepare_recipe') 
                    ->label('Preparar')
                    ->color('success')
                    ->icon(Heroicon::RocketLaunch)
                    ->modalHeading('¿Seguro de querer hacer esta preparación?')
                 ->modalDescription('Las preparaciones deben de registrarse UNA VEZ CONCLUIDAS')

                 ->schema([
            Select::make('amount')
            ->label('¿Cuantos lotes desea registrar?')
            ->options([
                '1' => 'Un Lote (1x)',
                '2' => 'Dos Lotes (2x)',
                '3' => 'Tres Lotes (3x)',
                '4' => 'Cuatro Lotes (4x)',
            ])
            ->live()
            ->default('1')
            ->afterStateUpdated(function(Model $record,$state,$set){
            
              $set('lote',$state*$record->produced_quantity);
               //$model->produced_quantity;
        
            }
            
            )
            ->required(),
                
            TextEntry::make('lote')
                ->label('Cantidad Aproximada Creada'),
            ])
            ->action(function (Model $record,array $data) {
                    $receta = Recipe::with('details')->find($record->id);
                    /*Este for each es solamente provisional/borrador.
                    Necesito crear una logica que primero revise que si existan insumos suficientes antes
                    de proceder con la receta
                    */
                    


                    

                    $supplies = [];

                    foreach ($receta->details as $details)
                        {
                            $supply = supply::find($details->supply_id);
                            if($supply->stock< ($details->amount*$data['amount'])){
                                    Notification::make("Failure")
                                    ->title('Insumos insuficientes: '.$supply->name)
                                    ->danger()
                                    ->send();
                                    return;
                            }else{
                                $supply->stock = $supply->stock - ($details->amount*$data['amount']);
                                array_push($supplies,$supply);

                            }
                        }


                    foreach ($supplies as $supply){

                        //$supply->stock = $supply->stock - $details->amount;
                        $supply->save();
                        
                    }

                    
                    $product = Product::find($record->product_id);
                    $product->stock = $product->stock+ $record->produced_quantity;
                    $product->save();

                    $preparation_log = new PreparationLog();
                    $preparation_log->user_id=auth()->id(); //En algunos IDE esto da error, pero no es así.
                    $preparation_log->recipe_id= $record->id;
                    $preparation_log->produced_quantity = $record->produced_quantity;
                    $preparation_log->save();

                     Notification::make("Success")
                    ->title('Saved')
                    ->success()
                    ->send();
                    

                    }), 
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
