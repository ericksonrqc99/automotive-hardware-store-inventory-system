<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f2f4f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            color: #2c3e50;
        }

        h1,
        h2,
        h3 {
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 24px;
            font-weight: bold;
            color: #2980b9;
        }

        h2 {
            font-size: 18px;
            font-weight: 500;
            color: #555;
        }

        p,
        th,
        td {
            font-size: 14px;
            color: #666;
        }

        .header,
        .footer {
            text-align: center;
            padding: 10px 0;
        }

        .header h1 {
            font-size: 26px;
        }

        .header .sub-header {
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }

        /* Información de factura y cliente */
        .invoice-info,
        .client-info {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .invoice-info div,
        .client-info div {
            width: 48%;
        }

        /* Tabla de detalles */
        .invoice-details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fafafa;
        }

        .invoice-details th,
        .invoice-details td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .invoice-details th {
            background-color: #2980b9;
            color: #ffffff;
        }

        .invoice-details td {
            color: #333;
        }

        /* Sección de total */
        .total-section {
            width: 100%;
            margin-top: 20px;
        }

        .total-section tr td {
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        .total-section tr td.label {
            text-align: right;
            color: #555;
        }

        .total-section tr td.value {
            text-align: right;
            color: #2980b9;
        }

        /* Footer */
        .footer p {
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Encabezado de factura -->
        <div class="header">
            <h1>Tienda de Autopartes</h1>
            <p class="sub-header">Dirección de la tienda | Teléfono: +34 123 456 789 | Email: contacto@autopartes.com
            </p>
        </div>

        <!-- Información de factura y cliente -->
        <div class="invoice-info">
            <div>
                <h2>Factura #{{ $invoice->id }}</h2>
                <p>Fecha: {{ $invoice->created_at->format('d/m/Y') }}</p>
                <p>Vendedor: {{ $seller->name }}</p>
            </div>
            <div>
                <h2>Cliente</h2>
                <p>Nombre: {{ $client->name }}</p>
                <p>Documento: {{ $client->document_number }}</p>
                <p>Teléfono: {{ $client->phone }}</p>
            </div>
        </div>

        <!-- Detalles de productos -->
        <table class="invoice-details">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->details as $detail)
                <tr>
                    <td>{{ $detail->product_name }}</td>
                    <td>{{ $detail->quantity }}</td>
                    <td>${{ number_format($detail->unit_price, 2) }}</td>
                    <td>${{ number_format($detail->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Resumen de total -->
        <table class="total-section">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="value">${{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Descuento:</td>
                <td class="value">${{ number_format($invoice->discount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Impuestos:</td>
                <td class="value">${{ number_format($invoice->taxes, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Total:</td>
                <td class="value">${{ number_format($invoice->total, 2) }}</td>
            </tr>
        </table>

        <!-- Pie de página -->
        <div class="footer">
            <p>Gracias por su compra. Si tiene alguna pregunta, no dude en ponerse en contacto con nosotros.</p>
        </div>
    </div>

</body>

</html>