<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;


class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label("Cliente")
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            //Filtrar solo las ordenes que fueron hechas en caja
            ->modifyQueryUsing(function (Builder $query) { 
                return $query->where('type', 'in_store');  
              })

            ->filters([
                //
            ])
            ->recordActions([
                //EditAction::make(),
                Action::make('pdf') 
                    ->label('PDF')
                    ->color('success')
                    ->icon(Heroicon::Printer)
                    ->action(function (Model $record) {
                        return response()->streamDownload(function () use ($record) {
                            $product = new Product; //No me gusta hacer otra query para encontrar los detalles
                            echo Pdf::loadHtml(
                                Blade::render('OrderPdf', ['record' => $record,'product'=>$product])
                            )->stream();
                        }, $record->id . '.pdf');
                    }),
                    Action::make('view_order')
                        ->label("View")
                        ->icon(Heroicon::Eye)

                        ->modalSubmitAction(false)
                        ->schema([ 
                            Grid::make(2)
                            ->schema([
                            TextEntry::make('customer.name')
                            ->label('Cliente'),
                            TextEntry::make('cashier.name')
                            ->label('Recibido por:'),

                            TextEntry::make('total'),
                            TextEntry::make('discount')
                            ->columns(2)
                            ->suffix('%'),
                            TextEntry::make('subtotal'),
                            TextEntry::make('products')
                            ->state(function($record){
                                $html = '<ul>';
                            foreach ($record->details as $detail)
                            {
                            $product= Product::find($detail->product_id);
                            $html .= "<li>Nombre: {$product->name} Cantidad: {$detail->quantity}</li>";}
                            $html .= '</ul>';
                            return $html;})
                            ->html(), ])]),
                     Action::make('cancel_order')
                                ->label('Cancelar')
                                ->icon('heroicon-m-x-circle')
                                ->color('danger')
                                ->visible(fn (Order $record): bool => $record->status !== 'Canceled')
                                
                                ->requiresConfirmation()
                                ->modalHeading('¿Cancelar pedido?')
                                ->modalDescription('Esta acción no se puede deshacer y el inventario podría verse afectado.')
                                ->modalSubmitActionLabel('Sí, cancelar')
                                ->action(function (Order $record) {
                                    $record->update([
                                        'status' => 'Canceled',
                                    ]);

                                        Notification::make()
                                        ->title('Pedido cancelado')
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
