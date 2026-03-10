<?php

namespace App\Filament\Resources\PreparationLogs;

use App\Filament\Resources\PreparationLogs\Pages\CreatePreparationLog;
use App\Filament\Resources\PreparationLogs\Pages\EditPreparationLog;
use App\Filament\Resources\PreparationLogs\Pages\ListPreparationLogs;
use App\Filament\Resources\PreparationLogs\Pages\ViewPreparationLog;
use App\Filament\Resources\PreparationLogs\Schemas\PreparationLogForm;
use App\Filament\Resources\PreparationLogs\Schemas\PreparationLogInfolist;
use App\Filament\Resources\PreparationLogs\Tables\PreparationLogsTable;
use App\Models\PreparationLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PreparationLogResource extends Resource
{
    protected static ?string $model = PreparationLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Historial Preparaciones';

    public static function form(Schema $schema): Schema
    {
        return PreparationLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PreparationLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PreparationLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPreparationLogs::route('/'),
            'create' => CreatePreparationLog::route('/create'),
            'view' => ViewPreparationLog::route('/{record}'),
            'edit' => EditPreparationLog::route('/{record}/edit'),
        ];
    }
}
