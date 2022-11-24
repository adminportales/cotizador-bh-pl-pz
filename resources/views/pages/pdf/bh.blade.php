<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cotizacion BH</title>
    <link rel="stylesheet" href="quotesheet/bh/stylebh.css">
</head>

<body>
    @php
        $user = auth()->user();
        if (auth()->user()->id !== $quote->user_id) {
            $user = $quote->user;
        }
    @endphp
    <header>
        <img src="quotesheet/bh/fondo-azul-superior.jpg" alt="" srcset="" class="fondo-head">
        <table class="head content">
            <tr>
                <td style="width: 240px">Presupuesto </td>
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
                <td colspan="3" style="text-align: center">www.trademarket.com.mx</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; font-size: 10px;">San Andrés Atoto 155A Naucalpan de
                    Juárez, Méx. C.P. 53550
                    Tel. +52(55) 5290 9100</td>
            </tr>
        </table>
        <div style="text-align: right">
            <p class="content">Pagina <span class="pagenum"></span></p>
        </div>
    </footer>
    <div class="body-pdf">
        @if ($quote->logo != null)
            <div class="content" style="width: 50%; text-align: center; margin-bottom: 10px;">
                <img src="{{ $quote->logo }}"
                    style="max-height: 130px; width: auto;  max-width: 45%;margin-bottom: 10px;">
            </div>
        @endif
        @if ($nombreComercial)
            <p class="content" style="font-size: 20px;"> <b>{{ $nombreComercial->name }}</b></p>
        @else
            <p class="content" style="font-size: 20px;">
                <b>{{ $quote->latestQuotesUpdate->quotesInformation->company }}</b>
            </p>
        @endif
        <table class="cliente content">
            <tr>
                <td style="width: 50%">

                    <p>Contacto: {{ $quote->latestQuotesUpdate->quotesInformation->name }}</p>
                    <p>Departamento: {{ $quote->latestQuotesUpdate->quotesInformation->department }}</p>

                </td>
                <td style="width: 50%">
                    <p>Cotización: QS{{ $quote->id }}</p>
                    <p>Fecha Cotización: {{ $quote->updated_at->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
        <table class="content">
            <tr>
                <td> <img src="quotesheet/bh/icon-whatsapp.png" alt=""> </td>
                <td> {{ $user->phone == null ? 'Sin Dato' : $user->phone }} </td>
                <td><img src="quotesheet/bh/icon-email.png" alt=""></td>
                <td>{{ $user->email }}</td>
            </tr>
        </table>

        <div class="head-products"></div>
        <table class="content productos-body">
            <thead>
                <tr class="titulos">
                    <th style="width: 180px">Imagen de Referencia</th>
                    <th style="width: 120px">Nombre</th>
                    <th style="width: 80px">Personalización</th>
                    <th style="width: 60px">Cantidad</th>
                    <th style="width: 90px">Precio
                        Unitario</th>
                    <th style="width: 90px">Total</th>
                    <th style="width: 70px">Tiempo
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
                        <td style="width: 150px">
                            @if ($producto->image)
                                <img src="{{ $producto->image }}" width="180">
                            @else
                                <img src="img/default.jpg" width="180">
                            @endif
                        </td>
                        <td style="width: 120px">{{ $producto->name }}</td>
                        <td style="width: 80px">{{ $tecnica->tecnica }}
                            <p style="font-size: 14px"><b>Tintas: </b>{{ $item->color_logos }}</p>
                        </td>
                        <td style="width: 60px">{{ $item->cantidad }}</td>
                        <td style="width: 90px">${{ number_format($item->precio_unitario, 2, '.', ',') }}</td>
                        <td style="width: 70px">${{ number_format($item->precio_total, 2, '.', ',') }}
                            @if ($quote->iva_by_item)
                                <p style="font-size: 12px"><b>IVA:
                                    </b>${{ number_format($item->precio_total * 0.16, 2, '.', ',') }}
                                </p>
                            @endif
                        </td>
                        <td style="width: 90px; text-align: center;">{{ $item->dias_entrega }} <br> <span
                                style="font-size: 10px">días hábiles</span>
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
                    @if (strlen($quote->latestQuotesUpdate->quotesInformation->information) > 0)
                        <p>Información Adicional: {{ $quote->latestQuotesUpdate->quotesInformation->information }}</p>
                    @endif
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
                    <p><b>Subtotal: </b> $ {{ number_format($subtotal, 2, '.', ',') }}</p>
                    <p><b>Descuento: </b> $ {{ number_format($discount, 2, '.', ',') }}</p>
                    @if (!$quote->iva_by_item)
                        <p><b>IVA: </b> $ {{ number_format($iva, 2, '.', ',') }}</p>
                    @endif
                    <br>
                    <p><b>Total: </b>$ {{ number_format($subtotal - $discount + $iva, 2, '.', ',') }}</p>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <div class="content condiciones">
        <br><br>
        <p> Condiciones:</p>
        <ul>
            <li>Condiciones de pago acordadas con el vendedor</li>
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
