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

        .portada {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #first-page-img {
            width: 100vh;
            height: 100vh;
        }

        .body {
            /*    height: 17cm;
            margin: 2cm 2cm 2cm 2cm;
            margin-top: -19.5cm; */
            /* background-color: red; */
            width: 100%;
            height: 100%;
            background-image: url(quotesheet/bh/ppt/BHPORTADA.jpg);
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center center;


            font-family: Arial, Helvetica, sans-serif;
        }


        /*   .content {
            height: 100%;
            background-color: rgba(255, 255, 255, 0.62);
        } */

        /*  .table-content {
            width: 100%;
            height: 100%;

        } */

        /*  .table-content td {

            width: 100%;
            height: 100%;
            vertical-align: middle;
        } */

        /* Logos */
        /*   .logos {
            width: 100%;
            height: 120px;
            text-align: center;
        } */

        /*  .logo {
            width: auto;
            height: 100%;
            object-fit: cover;
        }
 */
        /* Informacion */
        /*   .client {
            margin-top: 35px;
            font-size: 30px;
            font-weight: bold;
        }

        .client p {
            margin: 0;
            text-align: center;
        } */

        /*   .client .name-customer {
            font-size: 25px;
            font-weight: bold;
        }

        .fecha {
            margin-top: 20px;
            font-size: 28px;
            font-weight: bold;
        }

        .fecha p {
            margin: 0;
            text-align: center;
        } */
    </style>
</head>

<body>
    <div class="body">
        <!-- Imagen en la última página dentro de la etiqueta img -->
        @if ($data['portada'] != '')
            <div id="first-page-img">
                <img src="{{ $data['portada'] }}" class="portada" alt="">
            </div>
        @else
            <div class="contain" style="height:72%; text-align: center; ">
                <table class="table-content" style="width: 100%;  height: 80%; vertical-align: middle">
                    <td style="width: 100%">
                        <div class="content-logos"
                            style="height:62%; text-align:center; margin: 1.2cm">
                            <img src="{{ $data['logo'] }}" class="logo"
                                style="height: auto; width: auto; max-width: 90%; max-height: 90%">
                            {{-- <img src="quotesheet/bh/logo.png" class="logo"> --}}
                        </div>
                    </td>

                </table>
                <div class="client" style="width: 100%; height: 20%; margin-top: 20px;">
                    <table class="" style=" width: 100%;">
                        <td class="name" style="color: white; text-align: center; font-size: 35px; margin-top: -50px">
                            <span style="font-size: 45px">
                                <strong>
                                    {{ $quote->latestQuotesUpdate->quotesInformation->name }}</strong>
                            </span>
                            <br>
                            <span class="fecha_cot" style="font-weight: bold;">FECHA DE COTIZACION:
                                {{ $quote->created_at->format('d/m/Y') }}</span>
                        </td>
                        {{-- <td>
                            <p>
                                @if ($nombreComercial)
                                    @if ($quote->show_tax)
                                        {{ $nombreComercial->name }}
                                    @else
                                        {{ $quote->latestQuotesUpdate->quotesInformation->company }}
                                    @endif
                                @else
                                    {{ $quote->latestQuotesUpdate->quotesInformation->company }}
                                @endif
                            </p>
                            <p class="name-customer">
                                {{ $quote->latestQuotesUpdate->quotesInformation->name }}
                            </p>
                            @if ($quote->latestQuotesUpdate->quotesInformation->department)
                                <p class="name-customer">
                                    {{ $quote->latestQuotesUpdate->quotesInformation->department }}
                                </p>
                                <p>Fecha Cotización: {{ $quote->created_at->format('d/m/Y') }}</ @endif
                        </td> --}}
                    </table>
                </div>

            </div>
        @endif
    </div>
</body>

</html>
