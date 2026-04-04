<?php
namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor
{
    case Pending = 'Pending';
    case Ready = 'Ready';
    case Completed = 'Completed';
    case Canceled = 'Canceled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Ready => 'Lista',
            self::Completed => 'Completada',
            self::Canceled => 'Cancelada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Ready => 'info',
            self::Completed => 'success',
            self::Canceled => 'danger',
        };
    }
}
















?>