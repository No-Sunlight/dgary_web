<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\InventoryMovement;
use App\Models\PreparationLog;
use App\Models\Production;
use Barryvdh\DomPDF\Facade\Pdf;

class DynamicDashboard extends BaseDashboard
{
    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('download_orders')
                    ->label('Reporte de Ordenes (Caja/Gral)')
                    ->icon('heroicon-m-shopping-bag')
                    ->color('primary')
                    ->action(fn () => $this->downloadReport('orders')),

                Action::make('download_deliveries')
                    ->label('Reporte de Entregas a Domicilio')
                    ->icon('heroicon-m-truck')
                    ->color('success')
                    ->action(fn () => $this->downloadReport('deliveries')),
                    

                Action::make('download_production')
                    ->label('Reporte de Producción')
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->color('info')
                    ->action(fn () => $this->downloadReport('production')),

                Action::make('download_inventory')
                    ->label('Movimientos de Inventario')
                    ->icon('heroicon-m-arrows-right-left')
                    ->color('warning')
                    ->action(fn () => $this->downloadReport('inventory')),
            ])
            ->label('Descargar Reportes Semanales')
            ->icon('heroicon-m-arrow-down-tray')
            ->button()
            ->color('gray'),
        ];
    }

    public function downloadReport(string $type)
    {
        ini_set('memory_limit', '256M');
        $start = now()->startOfWeek();
        $end = now()->endOfWeek();

        switch ($type) {
            case 'orders':
                $data = Order::with('customer')->whereBetween('created_at', [$start, $end])->get();
                $view = 'orders-report';
                break;

            case 'deliveries':
                $data = Delivery::with(['driver', 'order.customer'])->whereBetween('created_at', [$start, $end])->get();
                $view = 'deliveries-report';
                break;

        case 'production':
                // Relación con el producto producido
                $data = Production::with(['product'])->whereBetween('created_at', [$start, $end])->get();
                $view = 'production-report';
                break;

            case 'inventory':
                // Relación con insumo o producto, y la producción relacionada
                $data = InventoryMovement::with(['supply', 'product', 'production'])
                    ->whereBetween('created_at', [$start, $end])
                    ->get();
                $view = 'inventory-report';
                break;
        }

        $pdf = Pdf::loadView($view, [
            'results' => $data,
            'date_range' => $start->format('d/m') . ' al ' . $end->format('d/m'),
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "Reporte_{$type}_" . now()->format('Y-m-d') . ".pdf"
        );
    }
}