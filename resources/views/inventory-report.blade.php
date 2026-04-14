<style>
    body { font-family: sans-serif; font-size: 10px; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #fff7ed; padding: 6px; border: 1px solid #fed7aa; }
    td { padding: 6px; border: 1px solid #eee; }
    .in { color: green; font-weight: bold; }
    .out { color: red; font-weight: bold; }
</style>
<h2>Movimientos de Inventario: {{ $date_range }}</h2>
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Ítem (Insumo/Producto)</th>
            <th>Sentido</th>
            <th style="text-align: right;">Cant.</th>
            <th>Motivo / Ref. Producción</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $mov)
        <tr>
            <td>{{ $mov->created_at->format('d/m H:i') }}</td>
            <td>{{ strtoupper($mov->type) }}</td>
            <td>
                @if($mov->supply_id) [INS] {{ $mov->supply->name ?? 'Insumo #'.$mov->supply_id }} @endif
                @if($mov->product_id) [PROD] {{ $mov->product->name ?? 'Prod #'.$mov->product_id }} @endif
            </td>
            <td class="{{ $mov->direction === 'in' ? 'in' : 'out' }}">
                {{ $mov->direction === 'in' ? 'ENTRADA (+)' : 'SALIDA (-)' }}
            </td>
            <td style="text-align: right;">{{ number_format($mov->quantity, 2) }}</td>
            <td>
                {{ $mov->reason }} 
                @if($mov->production_id) (Ref. Prod #{{ $mov->production_id }}) @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>