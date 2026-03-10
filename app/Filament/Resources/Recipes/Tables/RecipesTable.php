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
                    ->action(function (Model $record) {
                    $receta = Recipe::with('details')->find($record->id);
                    /*Este for each es solamente provisional/borrador.
                    Necesito crear una logica que primero revise que si existan insumos suficientes antes
                    de proceder con la receta
                    */

                    $supplies = [];

                    foreach ($receta->details as $details)
                        {
                            $supply = supply::find($details->supply_id);
                            if($supply->stock<$details->amount){
                                    Notification::make("Failure")
                                    ->title('Insumos insuficientes: '.$supply->name)
                                    ->danger()
                                    ->send();
                                    return;
                            }else{
                                $supply->stock = $supply->stock - $details->amount;
                                array_push($supplies,$supply);

                            }
                        }


                    foreach ($supplies as $supply){

                        $supply->stock = $supply->stock - $details->amount;
                        $supply->save();
                        
                    }

                    
                    $product = Product::find($record->product_id);
                    $product->stock = $product->stock+ $record->produced_quantity;
                    $product->save();

                    $preparation_log = new PreparationLog();
                    $preparation_log->user_id=auth()->id();    
                    $preparation_log->recipe_id= $record->id;
                    $preparation_log->produced_quantity = $record->produced_quantity;
                    $preparation_log->save();

                     Notification::make("Success")
                    ->title('Guardado')
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
