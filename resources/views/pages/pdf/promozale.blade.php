<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cotizacion Promo Zale</title>
    <link rel="stylesheet" href="quotesheet/pz/style.css">
</head>

<body>
    @php
        $user = auth()->user();
        if (auth()->user()->id !== $quote->user_id) {
            $user = $quote->user;
        }
    @endphp
    <header>
        <img src="quotesheet/pz/fondo-azul-superior.png" alt="" srcset="" class="fondo-head">
        <table class="head content">
            <tr>
                <td rowspan="3"><img src="quotesheet/pz/logo.jpg" class="logo"></td>
                <td colspan="6" class="company">PROMO ZALE S.A. DE C.V.</td>
            </tr>
            <tr>
                <td style="text-align: left;" colspan="6" class="company-quote">Cotizacion</td>
            </tr>
        </table>
    </header>
    <footer>
        <table
            style="magin-bottom: 0mm; position: absolute; bottom: 22mm; z-index: 20; width: 100%; margin-left: -10px;">
            <tr>
                <td>
                    <img src="quotesheet/pz/fondo-azul-inferior.png" />
                </td>
            </tr>
        </table>

        <table style="magin-bottom: 0mm; position: absolute; bottom: 60px; z-index:100;" class="content">
            <tr>
                <td>
                    <p style="font-size: 12px; margin-left:3px; color:#fff;">Pagina <span class="pagenum"></span> </p>
                    <br>
                    <p style="font-size: 12px; margin-left:3px; color:#fff; text-transform: uppercase">San Andr&#233;s
                        Atoto 155, San Est&#233;ban, Naucalpan, Edo. Méx. C.P. 53550 <br></p>
                </td>
            </tr>
        </table>
    </footer>
    <div class="body-pdf">
        @if ($quote->logo != null)
            <div class="content" style="width: 50%; text-align: center; margin-bottom: 10px;">
                <img src="{{ $quote->logo }}" style="max-height: 130px; width: auto">
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
                <td style="width: 20%">
                    <p>Contacto: </p>
                    <p>Departamento: </p>
                </td>

                <td style="width: 40%">
                    <p>{{ $quote->latestQuotesUpdate->quotesInformation->name }}</p>
                    <p> {{ $quote->latestQuotesUpdate->quotesInformation->department }}</p>
                </td>
                <td style="width: 40%">
                    <p># Cotización: QS{{ $quote->id }}</p>
                    <p>Fecha de cotización: {{ $quote->updated_at->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
        <div class="head-products content"></div>
        <table class="content productos-body">
            <thead>
                <tr class="titulos">
                    <th colspan="1">Imagen de Referencia</th>
                    <th colspan="1">Descripción</th>
                    <th colspan="1">Tecnica Personalización</th>
                    <th colspan="1">NÚMERO PIEZAS</th>
                    <th colspan="1">Tiempo
                        entrega</th>
                    <th colspan="1">Precio
                        Unitario</th>
                    <th colspan="1">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quote->latestQuotesUpdate->quoteProducts as $item)
                    @php
                        $producto = json_decode($item->product);
                        $tecnica = json_decode($item->technique);
                    @endphp
                    <tr>
                        <td rowspan="1">
                            @if ($producto->image)
                                <img src="{{ $producto->image }}" width="180">
                            @else
                                <img src="img/default.jpg" width="180">
                            @endif
                        </td>
                        <td colspan="1">{{ $producto->name }}</td>
                        <td colspan="1">{{ $tecnica->tecnica }}
                            <p style="font-size: 12px"><b>Tintas: </b>{{ $item->color_logos }}</p>
                        </td>
                        <td colspan="1">{{ $item->cantidad }}</td>
                        <td colspan="1" style="text-align: center;">{{ $item->dias_entrega }} <br> <span>días
                                hábiles
                            </span>
                        </td>
                        <td colspan="1">${{ number_format($item->precio_unitario, 2, '.', ',') }}
                        </td>
                        <td colspan="1">${{ number_format($item->precio_total, 2, '.', ',') }}
                            @if ($quote->iva_by_item)
                                <p style="font-size: 12px"><b>IVA:
                                    </b>${{ number_format($item->precio_total * 0.16, 2, '.', ',') }}
                                </p>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <table class="total content">
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
                <td style="width: 150px">
                    <p><b>Subtotal: </b>$ {{ number_format($subtotal, 2, '.', ',') }}</p>
                    <p><b>Descuento: </b>$ {{ number_format($discount, 2, '.', ',') }}</p>
                    @if (!$quote->iva_by_item)
                        <p><b>IVA: </b> $ {{ number_format($iva, 2, '.', ',') }}</p>
                    @endif
                    <br>
                    <p><b>Total: </b>$ {{ number_format($subtotal - $discount + $iva, 2, '.', ',') }}</p>
                </td>
            </tr>
        </table>
    </div>
    <div class="content condiciones">
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
                días sin previo aviso. Solo se bloquea el inventario al recibir Orden de Compra</li>
        </ul>
    </div>
    <div style="text-align: center;">
        <table class="content" style="width: 100%">
            <tr>
                <td style="text-align: right"><img src="quotesheet/bh/icon-email.png"alt=""> </td>
                <td>{{ $user->company->manager }} <b>GERENTE COMERCIAL</b></td>
                <td style="text-align: right"><img src="quotesheet/bh/icon-email.png"alt=""> </td>
                <td>{{ $user->name }} <b>KAM</b></td>
            </tr>
            <tr>
                <td style="text-align: right"><img src="quotesheet/bh/icon-email.png"alt=""> </td>
                <td style="vertical-align: middle">{{ $user->company->email }} </td>
                <td style="text-align: right"><img src="quotesheet/bh/icon-email.png" alt=""> </td>
                <td style="vertical-align: middle">{{ $user->email }}</td>
            </tr>
            <tr>
                <td style="text-align: right"><img src="quotesheet/bh/icon-whatsapp.png" alt=""> </td>
                <td style="vertical-align: middle"> {{ $user->company->phone }} </td>
                <td style="text-align: right"> <img src="quotesheet/bh/icon-whatsapp.png" alt=""> </td>
                <td style="vertical-align: middle"> {{ $user->phone }} </td>
            </tr>
        </table>
    </div>
</body>

</html>
