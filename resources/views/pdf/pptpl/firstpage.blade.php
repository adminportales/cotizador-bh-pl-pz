<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cotizacion PL</title>
    <style>
        @page {
            margin: 0cm;
            margin-right: 0cm;
            margin-top: 0cm;
            margin-bottom: 0cm;
        }

        #first-page-img {
            width: 100vh;
            height: 100vh;
        }

        .portada {
            width: 100%;
            height: 100vh;
            object-fit: cover;
        }



        body {
            background-image: url(quotesheet/pl/ppt/PLPORTADA.jpg);
            background-repeat: no-repeat;
            background-size: contain;
            background-position: center center;


            font-family: Arial, Helvetica, sans-serif;
            /* height: 17cm;
            margin: 2cm 2cm 2cm 2cm;
            margin-top: -19.5cm; */

        }

        .contain {
            margin: 1.2cm;
            height: 13.7cm;
        }

        .table {

            width: 100%;
        }


        .contenedor_logo {
            height: 100%;
            text-align: center;

        }

        .logo {
            width: auto;
            max-width: 90%;
            height: auto;
            max-height: 80%;
        }

        .datos {
            font-size: 24px;
            color: white;
            padding: 1.5px;
        }


        .tabla_datos {
            width: 100%;
        }

        .name {

            width: 50%;
            padding-left: 25px;
            font-size: 23px;

        }

        .fecha {

            width: 50%;
            text-align: right;
            font-size: 23px;
            padding-right: 25px;
        }
    </style>
</head>

<body>
    <!-- Imagen en la última página dentro de la etiqueta img -->
    @if ($data['portada'] != '')
        <div id="first-page-img">
            <img src="{{ $data['portada'] }}" class="portada" alt="">
        </div>
    @else
        <div class="contain">
            <table class="table">
                <tr>
                    <td class="contenedor_logo">
                        <img src="{{ $data['logo'] }}" class="logo">
                    </td>
                </tr>
            </table>
            <div class="datos">
                <table class="tabla_datos">
                    <tr>
                        <td class="name"><span>
                                <strong>
                                    {{ $quote->latestQuotesUpdate->quotesInformation->name }}</strong>
                            </span></td>
                        <td class="fecha" style="width: 50%; text-align: right;">
                            <span class="fecha_cot">FECHA DE COTIZACION:
                                {{ $quote->created_at->format('d/m/Y') }}</span>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    @endif
</body>

</html>
