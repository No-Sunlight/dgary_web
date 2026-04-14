<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Pedidos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .date-range { color: #666; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f2f2f2; text-align: left; padding: 8px; border-bottom: 2px solid #ddd; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .total-row { font-weight: bold; background-color: #fafafa; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Pedidos</h1>
        <div class="date-range">Semana: {{ $date_range }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($results as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? 'N/A' }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="text-right">${{ number_format($order->total, 2) }}</td>
                </tr>
                @php $grandTotal += $order->total; @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL GENERAL:</td>
                <td class="text-right">${{ number_format($grandTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>