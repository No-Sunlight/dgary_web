
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Factura</title>
<div>Atendido por
<div>Cliente: {{ $record->customer->name }}</div>
<div>Status: {{ $record->status }}</div>
<div>Total: {{ $record->created_at }}</div>
<div>Order Date: {{ $record->updated_at }}</div>
@foreach ($record->details as $detail)
  <div> Producto: {{$product::find($detail->product_id)->name}} Cantidad: {{$detail->quantity}}</div>
@endforeach

<style>
