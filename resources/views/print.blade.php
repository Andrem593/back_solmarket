<!DOCTYPE html>
<html>
<head>
    <title>Imprimir Venta</title>
</head>
<body>
    <h3>RECIBO</h3>
    <p>Nombre: {{ $encabezado->client->nombres }}</p>
    <p>Fecha: {{ $encabezado->fecha}}</p>
    <p>saldo: {{ $encabezado->saldo}}</p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cant</th>
                <th>Pvp</th>
                <th>total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalle as $det)
                <tr>
                    <td>{{ $det->product->nombre }}</td>
                    <td>{{ $det->cantidad }}</td>
                    <td>${{ $det->precio }}</td>
                    <td>${{ $det->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    <p>Total: {{ $encabezado->total }}</p>    

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>