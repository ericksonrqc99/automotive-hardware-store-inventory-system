<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Venta #{{ $sale->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .sale-details {
            margin-bottom: 20px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Comprobante de Venta</h1>
        <p>Venta #{{ $sale->id }}</p>
    </div>

    <div class="sale-details">
        <p><strong>Fecha:</strong> {{ $sale->created_at->format('d/m/Y') }}</p>
        <p><strong>Cliente:</strong> {{ $sale->customer->name }}</p>
        <!-- Agregar más detalles según tu modelo -->
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ @money($item->price) }}</td>
                <td>{{ @money($item->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right"><strong>Total:</strong></td>
                <td>{{ @money($sale->total) }}</td>
            </tr>
        </tfoot>
    </table>
</body>

</html>