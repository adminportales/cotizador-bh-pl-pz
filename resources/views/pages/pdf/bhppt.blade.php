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
            object-fit: contain;
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

        #first-page-header{
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
                        <img src="{{ $data['encabezado'] }}" class="encabezado" alt="">
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
            <img src="{{ $data['pie_pagina'] }}" class="footer" alt="">
        </div>
    </div>
    <div class="products">
        @php
            $left = 0;

        @endphp
        @foreach ($quote->latestQuotesUpdate->quoteProducts as $item)
            <table style=" width: 100%;">
                @php
                    $producto = json_decode($item->product);
                    $tecnica = json_decode($item->technique);
                    $scales_info = json_decode($item->scales_info);
                    if ($item->quote_by_scales) {
                        $quote_scales = true;
                    }
                    $tdColocado = false;
                @endphp

                <tr>
                    @if ($left == 0 && $tdColocado == false)
                        <td style="width: 30%"></td>
                        @php
                            $left = 1;
                            $tdColocado = true;
                        @endphp
                    @endif
                    <td style="width: 280px;">
                        @if ($producto->image)
                            <img src="{{ $producto->image }}"
                                style="max-height: 280px;height:auto;max-width: 280px;width:auto;">
                        @else
                            <img src="img/default.jpg" width="180">
                        @endif
                    </td>
                    <td style="">
                        <p class="descripcion" style="font-size: 20px;">
                            {{ $item->new_description ? $item->new_description : $producto->description }}
                        </p>
                    </td>
                    @if ($left == 1 && $tdColocado == false)
                        <td style="width: 30%"></td>
                        @php
                            $left = 0;
                            $tdColocado = true;
                        @endphp
                    @endif
                </tr>
            </table>
        @endforeach
    </div>
</body>

</html>
