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

        @page :first {
            header: first-page-header;
        }

        @page :last {

            #page-header,
            #page-footer {
                display: none;
            }
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

        .portada {
            width: 100%;
            height: 100vh;
        }

        .content-portada {
            width: 100vh;
            height: 100vh;
            overflow: hidden;
            object-fit: cover;
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

        #first-page-header {
            margin-top: -100px;
            margin-bottom: -70px;
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
            padding: 0 7cm;
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

        strong {
            color: {{ $data['color_primario'] }};
        } */

        .products p {
            margin: 0;
        }

        .products .descripcion {
            font-size: 20px;
            font-weight: bold;
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <div id="first-page-header">
        <div class="content-portada">
            <img src="{{ $data['portada'] }}" class="portada" alt="">
        </div>
    </div>
    <div style="page-break-before: always;"></div>
    <div id="page-header">
        <table class="table-header">
            <tr>
                <td style="width: 20%; text-align: left">
                    <img src="quotesheet/bh/logo.png" class="logo">
                </td>
                <td style="width: 60%; text-align: center">
                    <div class="content-encabezado">
                        @if ($data['encabezado'] != '')
                            <img src="{{ $data['encabezado'] }}" class="encabezado" alt="">
                        @endif
                    </div>
                </td>
                <td style="width: 20%; text-align: right">
                    <div class="content-encabezado">
                        <img src="{{ $data['logo'] }}" class="encabezado" alt="">
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div id="page-footer">
        <div class="content-footer">
            @if ($data['pie_pagina'] != '')
                <img src="{{ $data['pie_pagina'] }}" class="footer" alt="">
            @endif
        </div>
    </div>
    @if ($data['productos_por_pagina'] == 1)
        <div class="products">
            @foreach ($quote->latestQuotesUpdate->quoteProducts as $item)
                <table style=" width: 100%; height: 5cm;">
                    @php
                        $producto = json_decode($item->product);
                        $tecnica = json_decode($item->technique);
                        $scales_info = json_decode($item->scales_info);
                        if ($item->quote_by_scales) {
                            $quote_scales = true;
                        }
                        dd(1);
                    @endphp
                    <tr style="vertical-align: middle; text-align:center">
                        <td style="vertical-align: middle; height: 16cm; text-align:center">
                            @if ($producto->image)
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
                                    $precioUnitario = $quote->currency_type == 'USD' ? $precioUnitario / $quote->currency : $precioUnitario;
                                    $precioTotal = $quote->currency_type == 'USD' ? $precioTotal / $quote->currency : $precioTotal;
                                    $totalIva = $quote->currency_type == 'USD' ? $totalIva / $quote->currency : $totalIva;
                                @endphp
                                <p>
                                    <strong>Cantidad: </strong> {{ $item->cantidad }} pz
                                </p>
                                <p>
                                    <strong>Precio: </strong> $ {{ number_format($precioUnitario, 4, '.', ',') }}
                                </p>
                            @endif
                        </td>
                    </tr>
                </table>
            @endforeach
        </div>
    @else
        <div class="products">
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
                                $precioUnitario = $quote->currency_type == 'USD' ? $precioUnitario / $quote->currency : $precioUnitario;
                                $precioTotal = $quote->currency_type == 'USD' ? $precioTotal / $quote->currency : $precioTotal;
                                $totalIva = $quote->currency_type == 'USD' ? $totalIva / $quote->currency : $totalIva;
                            @endphp
                            <p>
                                <strong>Cantidad: </strong> {{ $item->cantidad }} pz
                            </p>
                            <p>
                                <strong>Precio: </strong> $ {{ number_format($precioUnitario, 4, '.', ',') }}
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
        </div>
    @endif
</body>

</html>
