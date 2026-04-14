<style>
    body { font-family: sans-serif; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th { background: #f1f5f9; padding: 8px; border: 1px solid #cbd5e1; text-align: left; }
    td { padding: 8px; border: 1px solid #e2e8f0; }
</style>
<h2>Reporte de Producción: {{ $date_range }}</h2>
<table>
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Producto</th>
            <th style="text-align: right;">Cantidad</th>
            <th>Notas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $prod)
        <tr>
            <td>{{ $prod->created_at->format('d/m/Y H:i') }}</td>
            <td><strong>{{ $prod->product->name ?? 'N/A' }}</strong></td>
            <td style="text-align: right;">{{ $prod->quantity }}</td>
            <td>{{ $prod->notes ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>