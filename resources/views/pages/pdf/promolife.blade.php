<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cotizacion Promo Life</title>
    <link rel="stylesheet" href="quotesheet/pl/style.css">
</head>

<body>
    @php
        $user = auth()->user();
        if (auth()->user()->id !== $quote->user_id) {
            $user = $quote->user;
        }
    @endphp
    <header>
        {{-- <img src="quotesheet/pl/fondo-azul-superior.png" alt="" srcset="" class="fondo-head"> --}}
        <div class="fondo-head"></div>
        <div class="content" style="text-align: right">
            <img src="quotesheet/pl/logo.png" class="logo">
        </div>
        <div class="content">
            <div style="margin-top: -40px;">
                <div style="width: 280px; padding-bottom: 10px;">
                    <span class="titulo1">Cotizacion</span>
                </div>
                <div style="width: 280px; text-align: center">
                    <span class="titulo2">QUOTATION SHEET</span>
                </div>
            </div>
        </div>
    </header>
    <footer>
        <p style="font-size: 12px; margin-left:3px; color:#000; text-align: right;" class="content">Pagina <span
                class="pagenum"></span> </p>
        <table
            style="magin-bottom: 0mm; position: absolute; bottom: 27mm; z-index: 10; width: 100%;background-color: rebeccapurple">
            {{-- {{ dd(1) }} --}}
            <tr style="background-color: rebeccapurple">
                <td style="background-color: rebeccapurple">
                    <div class="url"style="font-size: 15px; color:#fff ;">
                        <b>www.promolife.com.mx</b>
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
        @if ($quote->logo != null)
            <div class="content" style="width: 280px; text-align: center; margin-bottom: 10px;">
                <img src="{{ $quote->logo }}"
                    style="max-height: 130px; width: auto; max-width: 260px;margin-bottom: 10px;">
            </div>
        @endif
        @if ($nombreComercial)
            <p class="content" style="font-size: 20px;"> <b>{{ $nombreComercial->name }}</b></p>
        @else
            <p class="content" style="font-size: 20px;">
                <b>{{ $quote->latestQuotesUpdate->quotesInformation->company }}</b>
            </p>
        @endif
        </p>
        <table class="cliente content">
            <tr>
                <td style="width: 60%">
                    <p><b>Contacto:</b> {{ $quote->latestQuotesUpdate->quotesInformation->name }}</p>
                    <p><b>Departamento:</b>
                        {{ $quote->latestQuotesUpdate->quotesInformation->department ? $quote->latestQuotesUpdate->quotesInformation->department : 'Sin Informacion' }}
                    </p>
                </td>
                <td style="width: 40%">
                    <p><b># Cotización:</b> QS{{ $quote->id }}</p>
                    <p><b>Fecha de cotización:</b> {{ $quote->updated_at->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
        <div class="head-products content"></div>
        <table class="content productos-body">
            <thead>
                <tr class="titulos">
                    <th colspan="1" style="width: 180px">Imagen de Referencia</th>
                    <th colspan="2" style="width: 120px">Descripcion</th>
                    <th colspan="1" style="width: 90px">Tecnica</th>
                    <th colspan="1" style="width: 70px">Número Piezas</th>
                    <th colspan="1" style="width: 90px">Tiempo entrega</th>
                    <th colspan="1" style="width: 90px">Precio Unitario</th>
                    <th colspan="1" style="width: 90px">Importe</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $st = isset($quote->preview) ? $quote->precio_total : $quote->latestQuotesUpdate->quoteProducts->sum('precio_total');
                    $taxFee = round($st * ($quote->latestQuotesUpdate->quotesInformation->tax_fee / 100), 2);
                    $totalProducts = isset($quote->preview) ? $quote->productos_total : $quote->latestQuotesUpdate->quoteProducts->sum('cantidad');
                    $taxFeeAddProduct = round($taxFee / $totalProducts, 2);
                @endphp
                @foreach ($quote->latestQuotesUpdate->quoteProducts as $item)
                    @php
                        $producto = json_decode($item->product);
                        $tecnica = json_decode($item->technique);
                    @endphp
                    <tr>

                        <td rowspan="1" style="width: 180px">
                            @if ($producto->image)
                                <img src="{{ $producto->image }}"
                                    style="max-height: 200px;height:auto;max-width: 180px;width:auto;">
                            @else
                                <img src="img/default.jpg" width="180">
                            @endif
                        </td>
                        <td colspan="2" style="width: 120px">
                            {{ $item->new_description ? $item->new_description : $producto->description }}</td>
                        <td style="width: 80px">{{ $tecnica->tecnica }}
                            @if ($tecnica->tecnica !== 'No Aplica')
                                <p style="font-size: 14px"><b>Tintas: </b>{{ $item->color_logos }}</p>
                            @endif
                        </td>
                        <td colspan="1" style="width: 60px">{{ $item->cantidad }}</td>
                        <td colspan="1" style="text-align: center; width: 90px">{{ $item->dias_entrega }} <br>
                            <span style="font-size: 10px">días
                                hábiles
                            </span>
                        </td>
                        <td colspan="1" style="width: 90px">
                            ${{ number_format($item->precio_unitario + $taxFeeAddProduct, 2, '.', ',') }}
                        </td>
                        <td colspan="1" style="width: 100px">
                            ${{ number_format($item->precio_total + $taxFeeAddProduct * $item->cantidad, 2, '.', ',') }}
                            @if ($quote->iva_by_item)
                                <p style="font-size: 12px"><b>IVA:
                                    </b>${{ number_format(($item->precio_total + $taxFeeAddProduct * $item->cantidad) * 0.16, 2, '.', ',') }}
                                </p>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
        <br>
        <div class="content">
            @if (strlen($quote->latestQuotesUpdate->quotesInformation->information) > 0)
                <p style="text-align: justify; font-size: 17px; line-height: 1.2rem"><span style="font-weight: bold">Información Adicional:</span>
                    {{ $quote->latestQuotesUpdate->quotesInformation->information }}</p>
                <br>
            @endif
        </div>
        <table class="total content">
            <tr>
                @php
                    $subtotal = (isset($quote->preview) ? $quote->precio_total : $quote->latestQuotesUpdate->quoteProducts->sum('precio_total')) + $taxFee;
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
                    @if ($discount > 0)
                        <p><b>Descuento: </b>$ {{ number_format($discount, 2, '.', ',') }}</p>
                    @endif
                    @if (!$quote->iva_by_item)
                        <p><b>IVA: </b> $ {{ number_format($iva, 2, '.', ',') }}</p>
                    @endif
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
            <li>Vigencia de la cotización 5 días naturales.</li>
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
        <table class="content responsable" style="width: 105%","text-align: center">
            {{-- Modificacion en caso del user_id 52 (Ivonne Lopez) con otro gerente de la empresa PROMO LIFE --}}
            <tr>
                <td><img src="quotesheet/bh/icon-email.png"alt="">
                    {{ $user->id == 52 || $user->id == 62 ? 'Ricardo Zamora Rodriguez' : $user->companySession->manager }}
                    <b>GERENTE COMERCIAL</b>
                </td>
                <td><img src="quotesheet/bh/icon-email.png"alt="">
                    {{ $user->name }} <b>KAM</b></td>
            </tr>
            <tr>
                <td><img src="quotesheet/bh/icon-email.png"alt="">
                    {{ $user->id == 52 || $user->id == 62 ? 'ricardo.zamora@promolife.com.mx' : $user->companySession->email }}
                </td>
                <td><img src="quotesheet/bh/icon-email.png" alt="">
                    {{ $user->email }} </td>
            </tr>
            <tr>
                <td>
                    <img src="quotesheet/bh/icon-whatsapp.png" alt="">
                    {{ $user->id == 52 || $user->id == 62 ? '55 1963 4472' : $user->companySession->phone }}
                </td>
                <td><img src="quotesheet/bh/icon-whatsapp.png" alt=""> {{ $user->phone }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
