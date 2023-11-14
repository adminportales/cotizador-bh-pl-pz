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

    /*     body {
            font-family: Arial, Helvetica, sans-serif;
        } */

        .portada {
            width: 100%;
            height: 100vh;
            object-fit: cover;
        }

        #last-page-img {
            width: 100vh;
            height: 100vh;
        }

     /*    .body {
            height: 19cm;
            margin: 1cm 1cm 1cm 1cm;
            margin-top: -20.5cm;
        } */

        .body {
            height: 100vh;
            background-image: url(quotesheet/pz/ppt/PSCONTRAPORTADA.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center left;
        }

       /*  .content {
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
        }

        .table-content {
            width: 100%;
            height: 100%;

        }

        .table-content td.td-content {

            width: 80%;
            height: 80%;
            vertical-align: middle;
            padding: 10%;
        }

        .contact {
            font-size: 30px;
            font-weight: bold;
        }

        .info-seller {
            width: 100%;
            margin-top: 20px;
            font-size: 20px;
        } */
    </style>
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
    @if ($data['contraportada'] != '')
        <div id="last-page-img">
            <img src="{{ $data['contraportada'] }}" class="portada" alt="">
        </div>
    @else

        <div class="body">
            <div class="contain" style="background-color: rgba(0, 0, 255, 0.429); width: 100%; height: 90%;">
                <table class="table-content">
                    <td class="td-content">

                        <table class="info-seller" style="background-color: rgba(255, 192, 203, 0.557); width: 100%; height: 40%;
                        padding: 20px;">

                            <tr>
                                <p style="vertical-align: middle; width: 100%;  background-color: rgba(0, 255, 255, 0.418)">
                                    {{ $user->phone == null ? 'Sin Dato' : $user->phone }} </p>
                                <p style="vertical-align: middle; width: 100%; background-color: rgba(222, 184, 135, 0.258)" >
                                    {{ $user->email }}</p>
                            </tr>
                        </table>
                        <div class="condiciones">
                            <p> Condiciones:</p>
                            <ul>
                                <li>Condiciones de pago acordadas con el vendedor</li>
                                <li>Precios unitarios mostrados antes de IVA</li>
                                <li>Precios mostrados en {{ $quote->currency_type == 'USD' ? 'dolares (USD)' : 'pesos mexicanos (MXP)' }}.</li>
                                <li>El importe cotizado corresponde a la cantidad de piezas y número de tintas arriba mencionadas, si se
                                    modifica
                                    el número de piezas el precio cambiaría.</li>
                                <li>El tiempo de entrega empieza a correr una vez recibida la Orden de Compra y autorizada la muestra física
                                    o
                                    virtual a solicitud del cliente.</li>
                                <li>Vigencia de la cotización {{ $quote->latestQuotesUpdate->quotesInformation->shelf_life ?: 5 }} días
                                    {{ $quote->type_days == 0 ? 'hábiles' : 'naturales' }}.</li>
                                <li>Producto cotizado de fabricación nacional o importación puede afinarse la fecha de entrega previo a la
                                    emisión
                                    de Orden de Compra.</li>
                                <li>Producto cotizado disponible en stock a la fecha de esta cotización puede modificarse al paso de los
                                    días sin previo aviso. Solo se bloquea el inventario al recibir Orden de Compra</li>
                            </ul>
                        </div>

                        {{-- <div>
                            <p colspan="3" style="text-align: center;font-size: 27px;margin-bottom: 0">www.trademarket.com.mx
                            </p>
                            <p colspan="3" style="text-align: center; font-size: 16px;  margin-bottom: 0">San Andrés Atoto 155A
                                Naucalpan de
                                Juárez, Méx. C.P. 53550
                                Tel. +52(55) 5290 9100</p>
                        </div> --}}
                </table>
            </div>
        </div>
    @endif
</body>

</html>
