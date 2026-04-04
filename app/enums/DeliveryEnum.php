<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case In_transit = 'in_transit';
    case Completed = 'completed';
    case Canceled = 'canceled';
    case Refund= 'refund';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::In_transit => 'in_transit',
            self::Completed => 'Completado',
            self::Canceled => 'Cancelado',
            self::Refund =>''
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::In_transit => 'info',
            self::Completed => 'success',
            self::Canceled => 'danger',
        };
    }
}


?>