<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cotización Promo Zale</title>
    <link rel="stylesheet" href="quotesheet/pz/style.css">
</head>

<body>
    @guest
        @php
            $user = $quote->user;
        @endphp
    @else
        @php
            $user = auth()->user();
            if (auth()->user()->id !== $quote->user_id) {
                $user = $quote->user;
            }
        @endphp
    @endguest
    <header>
        <img src="quotesheet/pz/fondo-azul-superior.png" alt="" srcset="" class="fondo-head">
        <table class="head content">
            <tr>
                <td rowspan="3"><img src="quotesheet/pz/logo.jpg" class="logo"></td>
                <td colspan="6" class="company">PROMO ZALE S.A. DE C.V.</td>
            </tr>
            <tr>
                <td style="text-align: left;" colspan="6" class="company-quote">Cotización</td>
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
                    <p style="font-size: 12px; margin-left:3px; color:#fff;">Página <span class="pagenum"></span> </p>
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
                <img src="{{ $quote->logo }}"
                    style="max-height: 130px; width: auto;max-width: 45%;margin-bottom: 10px;">
            </div>
        @endif
        @if ($nombreComercial)
            @if ($quote->latestQuotesUpdate->quotesInformation->show_tax)
                <p class="content" style="font-size: 20px;"> <b>{{ $nombreComercial->name }}</b></p>
            @else
                <p class="content" style="font-size: 20px;">
                    <b>{{ $quote->latestQuotesUpdate->quotesInformation->company }}</b>
                </p>
            @endif
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
                    <p>Fecha de cotización: {{ $quote->created_at->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
        <div class="content">
            <hr>
            <p style="font-size: 17px; padding: 0px 0 12px 0; font-weight: bold;">En respuesta a tu solicitud para
                cotizarte, ponemos a tu disposición las siguientes
                propuestas:
            </p>
        </div>
        @php
            $taxFee = 1 + ((int) $quote->latestQuotesUpdate->quotesInformation->tax_fee) / 100;
            $quote_scales = false;
        @endphp
        <div class="content body-products">
            <style>
                td {
                    vertical-align: top;
                }

                .body-products table {
                    page-break-inside: avoid;
                }

                .body-products tr.title {
                    background-color: #72C3D6;
                    color: white;
                    text-align: center;
                    font-size: 17px;
                    font-weight: bold;
                }

                .body-products td {
                    padding: 5px 0 5px 0;
                    font-size: 16px;
                }

                .body-products td .descripcion {
                    padding: 5px 5px 5px 5px;
                    text-align: justify;
                }

                .body-products td.title-tecnica {
                    text-align: center;
                    font-weight: bold;
                    color: white;
                    background-color: #72C3D6;
                }

                .body-products td.detalle-tecnica {
                    text-align: center;
                    color: black;
                    background-color: rgb(251, 251, 251);
                }

                .body-products td.title-entrega {
                    margin: 5px 0 5px 0;
                    text-align: center;
                    font-weight: bold;
                    color: black;
                    background-color: rgb(251, 251, 251);
                }

                .body-products td.title-cantidad {
                    text-align: center;
                    font-weight: bold;
                    color: white;
                    background-color: #72C3D6;
                }

                .body-products td.detalle-cantidad {
                    text-align: center;
                    color: black;
                    background-color: rgb(251, 251, 251);
                    vertical-align: middle;
                }
            </style>
            @foreach ($quote->latestQuotesUpdate->quoteProducts as $item)
                @php
                    $producto = json_decode($item->product);
                    $tecnica = json_decode($item->technique);
                    $scales_info = json_decode($item->scales_info);
                    if ($item->quote_by_scales) {
                        $quote_scales = true;
                    }
                @endphp
                <table style="border-collapse: collapse;width:100%;border: 1px solid #72C3D6;">
                    <tr class="title">
                        <td>
                            <p class="title-text">Imagen de referencia</p>
                        </td>
                        <td colspan="12">
                            <p class="title-text">Descripción</p>
                        </td>
                    </tr>
                    <tr>
                        <td rowspan="{{ $item->quote_by_scales ? 5 + count($scales_info) : 6 }}"
                            style="width: 230px; text-align: center; vertical-align: middle; padding: 0; border-right: 1px solid #72C3D6;">
                            @if ($producto->image)
                                
                                <img  style="max-height: 220px;height:auto;max-width: 220px;width:auto;" src="{{$producto->image}}" alt="">
                            @else
                                <img src="img/default.jpg" width="180">
                            @endif
                        </td>

                        <td colspan="12">
                            <p class="descripcion">
                                {{ $item->new_description ? $item->new_description : $producto->description }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" class="title-tecnica">Técnica de personalización</td>
                        <td colspan="6" class="title-tecnica">Detalle de la personalización</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="detalle-tecnica">
                            {{ $tecnica->tecnica }}
                        </td>
                        <td colspan="6" class="detalle-tecnica">
                            @if ($tecnica->tecnica !== 'No Aplica')
                                <p style="font-size: 14px"><b>Tintas: </b>{{ $item->color_logos }}</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td colspan="12" class="title-entrega">Tiempo de entrega: {{ $item->dias_entrega }} días
                            {{ $item->type_days == null
                                ? ($quote->type_days == 0
                                    ? 'hábiles'
                                    : 'naturales')
                                : ($item->type_days == 1
                                    ? 'hábiles'
                                    : ($item->type_days == 2
                                        ? 'naturales'
                                        : '')) }}.
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="title-cantidad">Cantidad</td>
                        <td colspan="4" class="title-cantidad">Precio unitario</td>
                        <td colspan="4" class="title-cantidad">Precio total</td>
                    </tr>
                    @if ($item->quote_by_scales)
                        @foreach ($scales_info as $scale)
                            @php
                                $precioUnitario = $scale->unit_price * $taxFee;
                                $precioTotal = $scale->total_price * $taxFee;
                                $totalIva = $scale->total_price * $taxFee * 0.16;
                                // $precioUnitario = $quote->currency_type == 'USD' ? $precioUnitario / $quote->currency : $precioUnitario;
                                // $precioTotal = $quote->currency_type == 'USD' ? $precioTotal / $quote->currency : $precioTotal;
                                // $totalIva = $quote->currency_type == 'USD' ? $totalIva / $quote->currency : $totalIva;
                            @endphp
                            <tr>
                                <td colspan="4" class="detalle-cantidad">{{ $scale->quantity }} pz</td>
                                <td colspan="4" class="detalle-cantidad">$
                                    {{ number_format($precioUnitario, 2, '.', ',') }}

                                </td>
                                <td colspan="4" class="detalle-cantidad">$
                                    {{ number_format($precioTotal, 2, '.', ',') }}
                                    @if ($quote->iva_by_item)
                                        <p style="font-size: 12px"><b>IVA:
                                            </b>${{ number_format($totalIva, 2, '.', ',') }}
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            @php
                                $precioUnitario = $item->precio_unitario * $taxFee;
                                $precioTotal = $item->precio_total * $taxFee;
                                $totalIva = $item->precio_total * $taxFee * 0.16;
                                // $precioUnitario = $quote->currency_type == 'USD' ? $precioUnitario / $quote->currency : $precioUnitario;
                                // $precioTotal = $quote->currency_type == 'USD' ? $precioTotal / $quote->currency : $precioTotal;
                                // $totalIva = $quote->currency_type == 'USD' ? $totalIva / $quote->currency : $totalIva;
                            @endphp
                            <td colspan="4" class="detalle-cantidad">{{ $item->cantidad }} pz</td>
                            <td colspan="4" class="detalle-cantidad">$
                                {{ number_format($precioUnitario, 2, '.', ',') }}

                            </td>
                            <td colspan="4" class="detalle-cantidad">$
                                {{ number_format($precioTotal, 2, '.', ',') }}
                                @if ($quote->iva_by_item)
                                    <p style="font-size: 12px"><b>IVA:
                                        </b>${{ number_format($totalIva, 2, '.', ',') }}
                                    </p>
                                @endif
                            </td>
                        </tr>
                    @endif
                </table>
                <div style="height: 20px;"></div>
            @endforeach
        </div>
        <br>
        <div class="content">
            @if (strlen($quote->latestQuotesUpdate->quotesInformation->information) > 0)
                <p style="text-align: justify"><span style="font-weight: bold">Información Adicional:</span>
                    {{ $quote->latestQuotesUpdate->quotesInformation->information }}</p>
                <br>
            @endif
        </div>
        @if (!$quote_scales)
            @if ($quote->show_total)
                <table class="total content">
                    <tr>
                        @php
                            $subtotal = (isset($quote->preview) ? $quote->precio_total : $quote->latestQuotesUpdate->quoteProducts->sum('precio_total')) * $taxFee;
                            $discount = 0;
                            if ($quote->latestQuotesUpdate->quoteDiscount->type == 'Fijo') {
                                $discount = $quote->latestQuotesUpdate->quoteDiscount->value;
                            } else {
                                $discount = round(($subtotal / 100) * $quote->latestQuotesUpdate->quoteDiscount->value, 2);
                            }
                            $iva = round($subtotal * 0.16, 2);

                            // $subtotal = $quote->currency_type == 'USD' ? $subtotal / $quote->currency : $subtotal;
                            // $discount = $quote->currency_type == 'USD' ? $discount / $quote->currency : $discount;
                            // $iva = $quote->currency_type == 'USD' ? $iva / $quote->currency : $iva;
                        @endphp
                        <td style="width: 150px">
                            <p><b>Subtotal: </b>$ {{ number_format($subtotal, 2, '.', ',') }}</p>
                            @if ($discount > 0)
                                <p><b>Descuento: </b>$ {{ number_format($discount, 2, '.', ',') }}</p>
                            @endif
                            @if (!$quote->iva_by_item)
                                <p><b>IVA: </b> $ {{ number_format($iva, 2, '.', ',') }}</p>
                            @endif
                            <br>
                            <p><b>Total: </b>$ {{ number_format($subtotal - $discount + $iva, 2, '.', ',') }}</p>
                        </td>
                    </tr>
                </table>
            @endif
        @endif
    </div>
    <div class="content condiciones">
        <p> Condiciones:</p>
        <ul>
            <li>Condiciones de pago acordadas con el vendedor</li>
            <li>Precios unitarios mostrados antes de IVA</li>
            <li>Precios mostrados en {{ $quote->latestQuotesUpdate->quotesInformation->currency_type == 'USD' ? 'dolares (USD)' : 'pesos mexicanos (MXP)' }}.</li>
            <li>El importe cotizado corresponde a la cantidad de piezas y número de tintas arriba mencionadas, si se
                modifica
                el número de piezas el precio cambiaría.</li>
            <li>El tiempo de entrega empieza a correr una vez recibida la orden de compra y autorizada la muestra física
                o
                virtual a solicitud del cliente.</li>
            <li>Vigencia de la cotización {{ $quote->latestQuotesUpdate->quotesInformation->shelf_life ?: 5 }} días
                {{ $quote->type_days == 0 ? 'hábiles' : 'naturales' }}.</li>
            <li>Producto cotizado de fabricación nacional o importación puede afinarse la fecha de entrega previo a la
                emisión
                de orden de compra.</li>
            <li>El producto cotizado, disponible en stock a la fecha de esta cotización, puede modificarse con el paso de los
                días sin previo aviso. Solo se bloquea el inventario al recibir la orden de compra.</li>
        </ul>
    </div>
    <div style="text-align: center;">
        <table class="content" style="width: 100%">
            <tr>
                <td style="text-align: right"><img src="quotesheet/bh/icon-email.png"alt=""> </td>
                <td style="vertical-align: middle">
                    {{ $user->id == 18 || $user->id == 25 ? 'Daniel Garcia' : $quote->company->manager }}
                    <b>{{ $user->id == 18 || $user->id == 25 ? 'KAM Junior' : 'Gerente Comercial' }}</b>
                </td>
                @if (!($user->id == 18 || $user->id == 25))
                    <td style="text-align: right"><img src="quotesheet/bh/icon-email.png"alt=""> </td>
                    <td style="vertical-align: middle">{{ $user->name }} <b>KAM</b></td>
                @endif
            </tr>
            <tr>
                <td style="text-align: right"><img src="quotesheet/bh/icon-email.png"alt=""> </td>
                <td style="vertical-align: middle">
                    {{ $user->id == 18 || $user->id == 25 ? 'ventas1promodreams@gmail.com' : $quote->company->email }}
                </td>
                @if (!($user->id == 18 || $user->id == 25))
                    <td style="text-align: right"><img src="quotesheet/bh/icon-email.png" alt=""> </td>
                    <td style="vertical-align: middle">{{ $user->email }}</td>
                @endif
            </tr>
            <tr>
                <td style="text-align: right"><img src="quotesheet/bh/icon-whatsapp.png" alt=""> </td>
                <td style="vertical-align: middle">
                    {{ $user->id == 18 || $user->id == 25 ? '55 6628 2051' : $quote->company->phone }} </td>
                @if (!($user->id == 18 || $user->id == 25))
                    <td style="text-align: right"> <img src="quotesheet/bh/icon-whatsapp.png" alt=""> </td>
                    <td style="vertical-align: middle"> {{ $user->phone }} </td>
                @endif
            </tr>
        </table>
    </div>
</body>

</html>
