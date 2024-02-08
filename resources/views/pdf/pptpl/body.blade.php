<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cotizacion BH</title>
    <style>
        @page {
            margin: 0cm;
            margin-right: 0cm;
            margin-top: 0cm;
            margin-bottom: 0cm;
        }

        @page {
            header: page-header;
            footer: page-footer;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            padding-top: 100px;
            padding-bottom: 70px;
        }


        .content-encabezado {
            width: 100vh;
            height: 80px;
            overflow: hidden;
            object-fit: contain;
        }

        .footer {
            width: auto;
            height: 70px;
        }

        .content-footer {
            width: 100vh;
            height: 70px;
            overflow: hidden;
            object-fit: contain;
        }

        .encabezado {
            width: auto;
            height: 80px;
        }

        #page-header,
        #page-footer {
            position: fixed;
            left: 0;
            right: 0;
        }

        #page-header,
        #page-footer {
            height: 80px;
            /* Ajusta la altura según tus preferencias */
            text-align: center;
            line-height: 50px;
            /* Centra verticalmente el texto */
            font-size: 14px;
            /* Tamaño del texto */
            font-weight: bold;
            /* Puedes ajustar la fuente y otros estilos según tus preferencias */
        }

        #page-header {
            margin-top: -90px;
        }

        #page-footer {
            height: 70px;
        }

        #page-footer {
            top: auto;
            bottom: 10;
        }

        .logo {
            width: 207px;
            height: 84px;
        }

        .table-header {
            width: 100%;
        }

        .table-header td {
            padding: 10px;
        }

        .products {
            padding: 0 3cm;
        }

        /* @if ($data['productos_por_pagina'] == 1)
        .products {
            padding: 0 7cm;
        }
        @else
            .products {
                padding: 0 2cm;
            }
        @endif
        */ strong {
            color: {{ $data['color_primario'] }};
        }

        .products p {
            margin: 0;
        }

        .products .descripcion {
            font-size: 20px;
            font-weight: bold;
            padding: 20px 0;
        }


        .text-background td {
            background-color: {{ $data['color_primario'] }};
        }
    </style>
</head>

<body>
    @if ($data['fondo'] != '')
        <style>
            body {
                height: 100vh;
                background-image: url({{ $data['fondo'] }});
                background-repeat: no-repeat;
                background-size: 100% 100%;
            }
        </style>
    @endif
    @if ($data['fondo'] == '')
        <div id="page-header">
            <table class="table-header">
                <tr>
                    <td style="width: 20%; text-align: left">
                        <img src="quotesheet/pl/logo.png" class="logo">
                    </td>
                    <td style="width: 60%; text-align: center">
                    </td>
                    <td style="width: 20%; text-align: right">
                        <div class="content-encabezado">
                            <img src="{{ $data['logo'] }}" class="encabezado" alt="">
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    @endif
    <div id="page-footer">
        <div class="content-footer">
        </div>
    </div>
    @php
        $taxFee = 1 + ((int) $quote->latestQuotesUpdate->quotesInformation->tax_fee) / 100;
        $quote_scales = false;
    @endphp
    <div class="products">
        @if ($data['productos_por_pagina'] == 1)
            @foreach ($quote->latestQuotesUpdate->quoteProducts as $item)
                <table style="width: 100%">
                    @php
                        $producto = json_decode($item->product);
                        $tecnica = json_decode($item->technique);
                        $scales_info = json_decode($item->scales_info);
                        if ($item->quote_by_scales) {
                            $quote_scales = true;
                        }
                    @endphp
                    <td
                        style="vertical-align: middle;max-height: 12cm; {{ $loop->last ? 'height: auto;' : 'height: 12cm;' }}">
                        <p style="margin: 0; font-size: 31px; font-weight: bold;">{{ $producto->name }}</p>
                        <table>
                            <td style="width:32%; vertical-align: middle">
                                {{--     @if ($producto->image)
                                    <img src="{{ $producto->image }}"
                                        style="max-height: 320px;height:auto;max-width: 100%;width:auto;">
                                @else
                                    <img src="img/default.jpg" width="180">
                                @endif --}}
                                @if ($producto->image)
                                    <img style="width:200px; height:240px; object-fit:contain;"
                                    src="{{$producto->image}}" alt="">
                                @else
                                    <img src="img/default.jpg" width="180">
                                @endif
                            </td>
                            
                            <td style="width: 3%; vertical-align: middle"></td>
                            <td style="width:65%; vertical-align: middle">
                                <p class="descripcion" style="font-size: 22px; text-align: justify">
                                    {{ Str::ucfirst($item->new_description ? $item->new_description : $producto->description) }}
                                </p>
                                <p style="font-size: 21px;"><strong>Tecnica de Personalizacion:
                                    </strong>{{ $tecnica->tecnica }}</p>
                                <p style="font-size: 21px;"><strong>Tintas: </strong>
                                    @if ($tecnica->tecnica !== 'No Aplica')
                                        {{ $item->color_logos }}
                                    @else
                                        No Aplica
                                    @endif
                                </p>
                                <p style="font-size: 21px;"> <strong>Tiempo de Entrega: </strong>
                                    {{ $item->dias_entrega }} días
                                    {{ $item->type_days == null
                                        ? ($quote->type_days == 0
                                            ? 'hábiles'
                                            : 'naturales')
                                        : ($item->type_days == 1
                                            ? 'hábiles'
                                            : ($item->type_days == 2
                                                ? 'naturales'
                                                : '')) }}.
                                </p>
                                <style>
                                    .precio {
                                        margin-top: 10px;
                                    }

                                    .precio td.title-cantidad {
                                        text-align: center;
                                        font-weight: bold;
                                    }

                                    .precio td.detalle-cantidad {
                                        text-align: center;
                                        color: black;
                                        background-color: rgb(255, 255, 255);
                                        vertical-align: middle;
                                    }
                                </style>
                                <table class="precio" style="font-size: 21px; width: 100%">
                                    <tr class="text-background">
                                        <td colspan="4" class="title-cantidad">Cantidad</td>
                                        <td colspan="4" class="title-cantidad">Precio Unitario</td>
                                        <td colspan="4" class="title-cantidad">Precio Total</td>
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
                                                <td colspan="4" class="detalle-cantidad">{{ $scale->quantity }} pz
                                                </td>
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
                                        @php
                                            $precioUnitario = $item->precio_unitario * $taxFee;
                                            $precioTotal = $item->precio_total * $taxFee;
                                            $totalIva = $item->precio_total * $taxFee * 0.16;
                                            // $precioUnitario = $quote->currency_type == 'USD' ? $precioUnitario / $quote->currency : $precioUnitario;
                                            // $precioTotal = $quote->currency_type == 'USD' ? $precioTotal / $quote->currency : $precioTotal;
                                            // $totalIva = $quote->currency_type == 'USD' ? $totalIva / $quote->currency : $totalIva;
                                        @endphp
                                        <tr>
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
                            </td>
                        </table>

                    </td>
                </table>
            @endforeach
        @else
            <table style="">
                @php
                    $contador = 0;
                @endphp
                @foreach ($quote->latestQuotesUpdate->quoteProducts as $item)
                    @php
                        $producto = json_decode($item->product);
                        $tecnica = json_decode($item->technique);
                        $scales_info = json_decode($item->scales_info);
                        if ($item->quote_by_scales) {
                            $quote_scales = true;
                        }
                    @endphp
                    @if ($contador == 0)
                        <tr style="vertical-align: middle; text-align:center;">
                    @endif
                    <td style="vertical-align: middle; height: 16cm; text-align:center; width: 50%">
                        @if ($producto->image)
                            {{ $product->image }}
                            <img src="{{ $producto->image }}"
                                style="max-height: 520px;height:auto;max-width: 520px;width:auto;">
                        @else
                            <img src="img/default.jpg" width="180">
                        @endif
                        <p class="descripcion" style="font-size: 20px;">
                            {{ $item->new_description ? $item->new_description : $producto->description }}
                        </p>
                        <p><strong>Tecnica de Personalizacion: </strong>{{ $tecnica->tecnica }}</p>
                        <p><strong>Tintas: </strong>
                            @if ($tecnica->tecnica !== 'No Aplica')
                                {{ $item->color_logos }}
                            @else
                                No Aplica
                            @endif
                        </p>
                        <p> <strong>Tiempo de Entrega: </strong> {{ $item->dias_entrega }} días
                            {{ $item->type_days == null
                                ? ($quote->type_days == 0
                                    ? 'hábiles'
                                    : 'naturales')
                                : ($item->type_days == 1
                                    ? 'hábiles'
                                    : ($item->type_days == 2
                                        ? 'naturales'
                                        : '')) }}.
                        </p>
                        @if (!$item->quote_by_scales)
                            @php
                                $precioUnitario = $item->precio_unitario;
                                $precioTotal = $item->precio_total;
                                $totalIva = $item->precio_total * 0.16;
                                // $precioUnitario = $quote->currency_type == 'USD' ? $precioUnitario / $quote->currency : $precioUnitario;
                                // $precioTotal = $quote->currency_type == 'USD' ? $precioTotal / $quote->currency : $precioTotal;
                                // $totalIva = $quote->currency_type == 'USD' ? $totalIva / $quote->currency : $totalIva;
                            @endphp
                            <p>
                                <strong>Cantidad: </strong> {{ $item->cantidad }} pz
                            </p>
                            <p>
                                <strong>Precio: </strong> $ {{ number_format($precioUnitario, 2, '.', ',') }}
                            </p>
                        @endif
                    </td>
                    @if ($contador == 1)
                        </tr>
                    @endif
                    @php
                        $contador++;
                        if ($contador == 2) {
                            $contador = 0;
                        }
                    @endphp
                @endforeach
            </table>
        @endif

        @if (!$quote_scales)
            @if ($quote->show_total)
                <div>
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
                    <div style="width: 100%; text-align: right; margin-top: 5px;">
                        <br>
                        <p><b>Subtotal: </b> $ {{ number_format($subtotal, 2, '.', ',') }}</p>
                        @if ($discount > 0)
                            <p><b>Descuento: </b>$ {{ number_format($discount, 2, '.', ',') }}</p>
                        @endif
                        @if (!$quote->iva_by_item)
                            <p><b>IVA: </b> $ {{ number_format($iva, 2, '.', ',') }}</p>
                        @endif
                        <p><b>Total: </b>$ {{ number_format($subtotal - $discount + $iva, 2, '.', ',') }}</p>
                    </div>
                </div>
            @endif
        @endif
    </div>
</body>

</html>
