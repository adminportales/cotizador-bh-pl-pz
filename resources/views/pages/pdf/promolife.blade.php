<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cotizacion Promo Life</title>
    <link rel="stylesheet" href="quotesheet/pl/style.css">
</head>

<body>
    <header>
        <img src="quotesheet/pl/fondo-azul-superior.png" alt="" srcset="" class="fondo-head">
        <div class="content" style="text-align: right">
            <img src="quotesheet/pl/logo.png" class="logo">
        </div>
        <div class="content">
            <div style="margin-top: -40px;">
                <div style="width: 280px; padding-bottom: 10px;">
                    <span class="titulo1">Cotizacion</span>
                </div>
                <div style="width: 280px; text-align: center">
                    <span class="titulo2">QUOTATION
                        SHEET</span>
                </div>
            </div>
        </div>
    </header>
    <footer>
        <p style="font-size: 12px; margin-left:3px; color:#000; text-align: right;" class="content">Pagina <span
                class="pagenum"></span> </p>
        <table style="magin-bottom: 0mm; position: absolute; bottom: 27mm; z-index: 10; width: 100%;">
            <tr>
                <td>
                    <div>
                        <img src="quotesheet/pl/fondo-azul-inferior.png" />
                        <div class="url"style="font-size: 15px; color:#fff ;">
                            <b>www.promolife.com.mx</b>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <table style="magin-bottom: 0mm; position: absolute; bottom:70px; z-index:100;" class="content">
            <tr>
                <td>
                    <p style="font-size: 12px; color:#0b216e ;">
                        <span style="top: -15px; left: 38px;">
                            San Andr&#233;s Atoto 155, San Est&#233;ban, Naucalpan, Edo. Méx. C.P. 53550 <br>
                    </p>
                    </span>
                    <p style="font-size: 12px; color:#0b216e ;"> Tel. +52(55) 62690017 </p>
                </td>
            </tr>
        </table>
    </footer>
    <div class="body-pdf">
        <table class="cliente content">
            <tr>
                <td style="width: 20%">
                    <p>Atención a:</p>
                    <p>Contacto: </p>
                    <p>Departamento: </p>
                </td>

                <td style="width: 40%">
                    <p> {{ $quote->latestQuotesUpdate->quotesInformation->company }}</p>
                    <p>{{ $quote->latestQuotesUpdate->quotesInformation->name }}</p>
                    <p> {{ $quote->latestQuotesUpdate->quotesInformation->department }}</p>
                </td>
                <td style="width: 40%">
                    <p>Costumer ID: Falta ese dato</p>
                    <p># Cotización: {{ $quote->id }}</p>
                    <p># Lead:{{ $quote->lead }}</p>
                    <p>Fecha Cotización: {{ $quote->updated_at->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
        <div class="head-products content"></div>
        <table class="content productos-body">
            <thead>
                <tr class="titulos">
                    <th colspan="1">Imagen Referencia</th>
                    <th colspan="2">Descripción</th>
                    <th colspan="1">Tecnica Personalización</th>
                    <th colspan="1">Tintas</th>
                    <th colspan="1">Número Piezas</th>
                    <th colspan="1">Tiempo entrega</th>
                    <th colspan="1">Precio Unitario</th>
                    <th colspan="1">Importe</th>
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
                                <img src="{{ $producto->image }}" width="40">
                            @else
                                <img src="img/default.jpg" width="40">
                            @endif

                        </td>
                        <td colspan="2">{{ $producto->description }}</td>
                        <td colspan="1">{{ $tecnica->tecnica }}</td>
                        <td colspan="1">{{ $item->color_logos }}</td>
                        <td colspan="1">{{ $item->cantidad }}</td>
                        <td colspan="1" style="text-align: center;">{{ $item->dias_entrega }} <br> <span
                                style="font-size: 10px">días
                                hábiles
                            </span>
                        </td>
                        <td colspan="1">${{ $item->precio_unitario }}</td>
                        <td colspan="1">${{ $item->precio_total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
        <br><br>
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
                    <p><b>Subtotal: </b>$ {{ $subtotal }}</p>
                    <p><b>Descuento: </b>$ {{ $discount }}</p>
                    <p><b>Iva: </b>$ {{ $iva }}</p>
                    <p><b>Total: </b>$ {{ $subtotal - $discount + $iva }}</p>
                </td>
            </tr>
        </table>
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
    <div>
        <div class="firma content" style="text-align: center">
            <p>Firma Digital</p>
            <p class="linea">__________________________</p>
        </div>
        <br>
        <table class="content responsable" style="width: 105%","text-align: center">
            <tr>
                <td><img src="quotesheet/bh/icon-email.png"alt="">{{ auth()->user()->company->manager }}
                    <b>GERENTE COMERCIAL</b>
                </td>
                <td><img src="quotesheet/bh/icon-email.png"alt="">
                    {{ auth()->user()->name }} <b>KAM</b></td>
            </tr>
            <tr>
                <td><img src="quotesheet/bh/icon-email.png"alt="">
                    {{ auth()->user()->company->email }}</td>
                <td><img src="quotesheet/bh/icon-email.png" alt="">
                    {{ auth()->user()->email }} </td>
            </tr>
            <tr>
                <td><img src="quotesheet/bh/icon-whatsapp.png" alt=""> {{ auth()->user()->company->phone }}
                </td>
                <td><img src="quotesheet/bh/icon-whatsapp.png" alt=""> +52 55 5555
                    5555</td>
            </tr>
        </table>
    </div>
</body>

</html>
