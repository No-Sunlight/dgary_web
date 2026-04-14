<style>body { font-family: sans-serif; font-size: 11px; } table { width: 100%; border-collapse: collapse; } th { background: #dcfce7; padding: 5px; border: 1px solid #86efac; } td { padding: 5px; border: 1px solid #eee; }</style>
<h2>Entregas a Domicilio: {{ $date_range }}</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Repartidor</th>
            <th>Cliente</th>
            <th>Dirección</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($results as $delivery)
        <tr>
            <td>#{{ $delivery->id }}</td>
            <td>{{ $delivery->driver->name ?? 'Sin asignar' }}</td>
            <td>{{ $delivery->order->customer->name ?? 'N/A' }}</td>
            <td>{{ $delivery->address }}</td>
            <td>{{ $delivery->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>