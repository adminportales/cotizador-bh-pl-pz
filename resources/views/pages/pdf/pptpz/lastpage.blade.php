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
            <div class="contain" style=" height: 14.8cm;">
                <div style=" width: 90%; height: 100%; margin: 1.2cm;">
                    <table class="info-seller" style=" width: 86%; height: 40%; margin-left: 168px;">

                        <tr style=" width: 100%;">
                            <td style=" width: 100%; height: 30px; color: white; font-size: 22px; font-weight: bold;">
                                <p style="vertical-align: middle; width: 100%;   margin-top: 112px;">
                                    {{ $user->phone == null ? 'Sin Dato' : $user->phone }} </p>
                                <p style="vertical-align: middle; width: 100%; margin-top:35px;">
                                    {{ $user->email }}</p>
                            </td>
                        </tr>
                    </table>

                    <div class="condiciones" style="width: 98%; margin-left: 20px; margin-top: -20px;">

                        <ul style="color: white; font-size: 19px; font-family: sans-serif; font-weight: bold;">
                            <p> Condiciones:</p>
                            <span style="">-Condiciones de pago acordadas con el
                                vendedor</span>
                            <br>
                            <span style="white-space:pre-line">-Precios unitarios mostrados antes de IVA
                            </span>
                            <span>-Precios mostrados en
                                {{ $quote->currency_type == 'USD' ? 'dolares (USD)' : 'pesos mexicanos (MXP)' }}.
                            </span>
                            <br>
                            <span style="white-space: normal">-El importe cotizado corresponde a la cantidad
                                de
                                piezas y número de
                                tintas
                                arriba
                                mencionadas, si se
                                <br> modifica
                                el número de piezas el precio cambiaría.</span>
                            <br>
                            <span>-El tiempo de entrega empieza a correr una vez recibida la Orden de Compra
                                y
                                autorizada la muestra <br>física
                                o
                                virtual a solicitud del cliente.</span>

                            <span style="white-space:normal">
                                -Vigencia de la cotización
                                {{ $quote->latestQuotesUpdate->quotesInformation->shelf_life ?: 5 }}
                                días
                                {{ $quote->type_days == 0 ? 'hábiles' : 'naturales' }}.
                            </span>
                            <br>
                            <span>-Producto cotizado de fabricación nacional o importación puede afinarse
                                la
                                fecha de
                                entrega previo a la<br>
                                emisión
                                de Orden de Compra.</span>
                            <br>
                            <span>-Producto cotizado disponible en stock a la fecha de esta cotización
                                puede
                                modificarse al paso de los<br>
                                días
                                sin
                                previo aviso. Solo se bloquea el inventario al recibir Orden de
                                Compra</span>
                        </ul>
                    </div>
                </div>
                {{-- <div>
                            <p colspan="3" style="text-align: center;font-size: 27px;margin-bottom: 0">www.trademarket.com.mx
                            </p>
                            <p colspan="3" style="text-align: center; font-size: 16px;  margin-bottom: 0">San Andrés Atoto 155A
                                Naucalpan de
                                Juárez, Méx. C.P. 53550
                                Tel. +52(55) 5290 9100</p>
                        </div> --}}

            </div>
        </div>
    @endif
</body>

</html>
