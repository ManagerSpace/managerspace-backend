<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .invoice-summary {
            text-align: right;
        }
        .invoice-notes {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
<div class="invoice-header">
    <h1>Factura #{{ $invoice->id }}</h1>
</div>

<div class="invoice-details">
    <p><strong>Empresa:</strong> {{ $invoice->company->name }}</p>
    <p><strong>Cliente:</strong> {{ $invoice->client->name }}</p>
    <p><strong>Proyecto:</strong> {{ $invoice->project->name ?? 'N/A' }}</p>
    <p><strong>Fecha de emisión:</strong> {{ $invoice->issue_date->format('d/m/Y') }}</p>
    <p><strong>Estado:</strong> {{ ucfirst(__($invoice->status)) }}</p>
    <p><strong>Tipo:</strong> {{ ucfirst(__($invoice->type === 'expense' ? 'Gasto' : 'Ingreso')) }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>Producto</th>
        <th>Descripción</th>
        <th>Cantidad</th>
        <th>Precio</th>
        <th>Impuesto</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>{{ $product->description }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ number_format($product->price, 2, ',', '.') }} €</td>
            <td>{{ $product->tax->rate }}%</td>
            <td>{{ number_format($product->quantity * $product->price * (1 + $product->tax->rate / 100), 2, ',', '.') }} €</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="invoice-summary">
    <p><strong>Subtotal:</strong> {{ number_format($invoice->subtotal, 2, ',', '.') }} €</p>
    <p><strong>Impuestos:</strong> {{ number_format($invoice->tax_amount, 2, ',', '.') }} €</p>
    <p><strong>Total:</strong> {{ number_format($invoice->total, 2, ',', '.') }} €</p>
</div>

@if($invoice->notes)
    <div class="invoice-notes">
        <h3>Notas:</h3>
        <p>{{ $invoice->notes }}</p>
    </div>
@endif
</body>
</html>
