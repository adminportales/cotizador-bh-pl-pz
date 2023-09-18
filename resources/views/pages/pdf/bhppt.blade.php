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
            height: 50px;
        }

        .content-footer {
            width: 100vh;
            height: 50px;
            overflow: hidden;
            object-fit: contain;
        }

        .encabezado {
            width: auto;
            height: 80px;
        }
        .products{
            margin-top: 80px;
            margin-bottom: 50px;
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

        #page-footer {
            height: 50px;
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
        @foreach ($quote->latestQuotesUpdate->quoteProducts as $item)
            @php
                $producto = json_decode($item->product);
                $tecnica = json_decode($item->technique);
                $scales_info = json_decode($item->scales_info);
                if ($item->quote_by_scales) {
                    $quote_scales = true;
                }
            @endphp

            @if ($producto->image)
                <img src="{{ $producto->image }}" style="max-height: 220px;height:auto;max-width: 220px;width:auto;">
            @else
                <img src="img/default.jpg" width="180">
            @endif
            <p class="descripcion">
                {{ $item->new_description ? $item->new_description : $producto->description }}
            </p>

            <div style="height: 20px;"></div>
        @endforeach
    </div>
</body>

</html>
