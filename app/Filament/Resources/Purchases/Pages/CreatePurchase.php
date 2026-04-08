<?php

namespace App\Filament\Resources\Purchases\Pages;

use App\Filament\Resources\Purchases\PurchaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchase extends CreateRecord
{
    protected static string $resource = PurchaseResource::class;

    protected function afterCreate(): void
    {
        $total = $this->record->details()->sum('subtotal');
        $this->record->update(['total' => $total]);

        // actualizar stock
        foreach ($this->record->details as $detail) {
            $supply = $detail->supply;
            $supply->stock += $detail->quantity;
            $supply->save();
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['total'] = collect($data['details'] ?? [])
            ->pluck('subtotal')
            ->sum();

        return $data;
    }
}
