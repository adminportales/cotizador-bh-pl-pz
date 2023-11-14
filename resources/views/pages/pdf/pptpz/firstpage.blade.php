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
            height: 100vh;
            object-fit: cover;
        }

        #first-page-img {
            width: 100vh;
            height: 100vh;
        }

        /*   .body {
            height: 17cm;
            margin: 2cm 2cm 2cm 2cm;
            margin-top: -19.5cm;

        } */

        .body {
            height: 100vh;
            background-image: url(quotesheet/pz/ppt/PSPORTADA.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center left;
            /* background-color: rgb(119, 194, 255); */
        }

        /*   .content {
            height: 100%;
            background-color: rgba(255, 255, 255, 0.62);
        }

        .table-content {
            width: 100%;
            height: 100%;

        }

        .table-content td {

            width: 100%;
            height: 100%;
            vertical-align: middle;
        } */

        /* Logos */
        /*   .logos {
            width: 100%;
            height: 120px;
            text-align: center;
        }

        .logo {
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
        }

        .client .name-customer {
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
            {{-- Esta portada va el logo, nombre del vendedor y cliente --}}

            <div class="contain"
                style="width: 100%; height:90%;">
                <table class="table-content" style="width: 100%; height:75%; background-color:">
                    <td class="contenedor_logo"
                        style="width: 100%; height: 10%; text-align: right; padding: 18px;">
                        <img src="{{ $data['logo'] }}" class="logo"
                            style="height: auto; width: auto; max-width: 54%; max-height: 54%; padding-right: 15px">
                    </td>
                </table>
                <div class="client"
                    style="width: 100%; height: 30%; margin-top: -62px;">
                    <table style=" width: 100%; height: 68%; text-align:center; font-weight: bold; font-family: sans-serif;">
                        <td class="name" style="color: black; width:25%;">
                        </td>
                        <td class="name" style="color: black; width:75%;">
                            <span style="font-size: 60px">
                                <strong>
                                    {{ $quote->latestQuotesUpdate->quotesInformation->name }}</strong>

                            </span>
                            <br>
                            <br>
                            <span class="fecha_cot" style="font-weight: bold; font-size: 30px;">FECHA DE COTIZACION:
                                {{ $quote->created_at->format('d/m/Y') }}</span>
                        </td>
                    </table>
                </div>


            </div>
    </div>
    @endif
</body>

</html>
