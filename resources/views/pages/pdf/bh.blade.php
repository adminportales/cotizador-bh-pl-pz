<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cotizacion BH</title>
    <link rel="stylesheet" href="quotesheet/bh/stylebh.css">
</head>

<body>
    <header>
        <img src="quotesheet/bh/fondo-azul-superior.jpg" alt="" srcset="" class="fondo-head">
        <table class="head content">
            <tr>
                <td style="width: 240px">Presupuesto <br> Budget</td>
                <td class="separator">Compañia</td>
                <td></td>
                <td style="text-align: center"><img src="quotesheet/bh/logo.png" class="logo"></td>
            </tr>
        </table>
        <p class="company-name content">BH Trade Market S.A. de C.V.</p>
    </header>
    <footer>
        <table class="footer content">
            <tr>
                <td>{Responsable}</td>
                <td>Gerente Comercial</td>
                <td>gerencia@trademarket.com.mx</td>
                <td>{+52 55 5555 5555}</td>
            </tr>
            <tr>
                <td colspan="4" style="height: 10px"></td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center">www.bhtrademarket.com.mx</td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center; font-size: 10px;">San Andrés Atoto 155A Naucalpan de
                    Juárez, Méx. C.P. 53550
                    Tel. +52(55) 5290 9100</td>
            </tr>
        </table>
        <div style="text-align: right">
            <p class="content">Pagina <span class="pagenum"></span></p>
        </div>
    </footer>
    <div class="body-pdf">
        <table class="cliente content">
            <tr>
                <td style="width: 50%">

                    <p>Atención a: {{ $quote->latestQuotesUpdate->quotesInformation->company }}</p>
                    <p>Contacto: {{ $quote->latestQuotesUpdate->quotesInformation->name }}</p>
                    <p>Departamento: {{ $quote->latestQuotesUpdate->quotesInformation->department }}</p>

                </td>
                <td style="width: 50%">
                    <p>Cotización: {{ $quote->id }}</p>
                    <p>Fecha Cotización: {{ $quote->updated_at->format('d/m/Y') }}</p>
                    <p>Lead: {{ $quote->lead }}</p>
                </td>
            </tr>
        </table>
        <table class="content">
            <tr>
                <td><img src="quotesheet/bh/icon-whatsapp.png" alt=""> </td>
                <td>+52 55 5555 5555 </td>
                <td><img src="quotesheet/bh/icon-email.png" alt=""></td>
                <td>{{ auth()->user()->email }}</td>
            </tr>
        </table>
        <br>
        <div class="head-products"></div>
        <table class="content productos-body">
            <thead>
                <tr class="titulos">
                    <th colspan="1">Imagen</th>
                    <th colspan="2">Descripción</th>
                    <th colspan="1">Personalización</th>
                    <th colspan="1">Tintas</th>
                    <th colspan="1">Cantidad</th>
                    <th colspan="1">Precio
                        Unitario</th>
                    <th colspan="1">Total</th>
                    <th colspan="1">Tiempo
                        de entrega</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quote->latestQuotesUpdate->quoteProducts as $item)
                    @php
                        $producto = json_decode($item->product);
                        $tecnica = json_decode($item->technique);
                    @endphp
                    <tr>

                        <td colspan="1">Imagen</td>
                        <td colspan="2">{{ $producto->description }}</td>
                        <td colspan="1">{{ $tecnica->tecnica }}</td>
                        <td colspan="1">{{ $item->color_logos }}</td>
                        <td colspan="1">{{ $item->cantidad }}</td>
                        <td colspan="1">${{ $item->precio_unitario }}</td>
                        <td colspan="1">${{ $item->precio_total }}</td>
                        <td colspan="1" style="text-align: center;">{{ $item->dias_entrega }} <br> <span
                                style="font-size: 10px">días
                                hábiles
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <br>
        <table class="total content">
            <tr style="">
                <td style="width: 100%; font-size: 15px;">
                    <p style="margin-bottom: 5px">Cotización Válida Hasta:
                        {{ $quote->updated_at->addMonth()->format('d/m/Y') }}</p>
                    <br>
                    <p>Información Adicional: {{ $quote->latestQuotesUpdate->quotesInformation->information }}</p>
                    <br>
                </td>
            </tr>
            <tr>
                @php
                    $subtotal = $quote->latestQuotesUpdate->quoteProducts->sum('precio_total');
                    $discount = 0;
                    if ($quote->latestQuotesUpdate->quoteDiscount->type == 'Fijo') {
                        $discount = $quote->latestQuotesUpdate->quoteDiscount->value;
                    } else {
                        $discount = round(($subtotal / 100) * $quote->latestQuotesUpdate->quoteDiscount->value, 2);
                    }
                    $iva = round($subtotal * 0.16, 2);
                @endphp
                <td style="width: 100%; text-align: right">
                    <p><b>Subtotal: </b>$ {{ $subtotal }}</p>
                    <p><b>Descuento: </b>$ {{ $discount }}</p>
                    <p><b>Iva: </b>$ {{ $iva }}</p>
                    <p><b>Total: </b>$ {{ $subtotal - $discount + $iva }}</p>
                </td>
            </tr>
        </table>
        <div class="firma content">
            <br><br><br>
            <p style="height: 45px"></p>
            <p class="linea">______________________________</p>
            <p>Firma Vendedor</p>
            <p>{{ auth()->user()->name }}</p>
        </div>
    </div>

    <div class="content condiciones">
        <p> Condiciones:</p>
        <ul>
            <li>Condiciones de pago {plazos de pago}</li>
            <li>Precios unitarios mostrados antes de IVA</li>
            <li>Precios mostrados en pesos mexicanos (MXP)</li>
            <li>El importe cotizado corresponde a la cantidad de piezas y número de tintas arriba mencionadas, si se
                modifica
                el número de piezas el precio cambiaría.</li>
            <li>El tiempo de entrega empieza a correr una vez recibida la Orden de Compra y autorizada la muestra física
                o
                virtual a solicitud del cliente.</li>
            <li>Vigencia de la cotización 30 días naturales.</li>
            <li>Producto cotizado de fabricación nacional o importación puede afinarse la fecha de entrega previo a la
                emisión
                de Orden de Compra.</li>
            <li>Producto cotizado disponible en stock a la fecha de esta cotización puede modificarse al paso de los
                días
                sin
                previo aviso. Solo se bloquea el inventario al recibir Orden de Compra</li>
        </ul>
    </div>
</body>

</html>
