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

        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .portada {
            width: 100%;
            height: 100vh;
            object-fit: cover;
        }

        #last-page-img {
            width: 100vh;
            height: 100vh;
        }

        /*  .body {
            height: 19cm;
            margin: 1cm 1cm 1cm 1cm;
            margin-top: -20.5cm;
        } */

        .body {
            height: 100vh;
            background-image: url(quotesheet/bh/ppt/BHCONTRAPORTADA.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center left;
        }

        /*  .content {
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
        } */

        /*   .table-content {
            width: 100%;
            height: 100%;

        }

        .table-content td.td-content {

            width: 80%;
            height: 80%;
            vertical-align: middle;
            padding: 10%;
        }
 */
        /*  .contact {
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
            <div class="content" style="width: 100%; height:80%;">
                <table class="table-content"
                    style="height: 88%; width: 100%;">

                    {{-- <div class="contact">
                            Contacto
                        </div> --}}
                    <table class="info-seller" style="width: 100%; background-color:height: 20%;  margin-top: 3cm;">
                        {{--   <tr>
                                <td colspan="2">
                                    <p style="font-size:20px;  font-weight: bold">
                                    {{ $user->name }}
                                    </p>
                                </td>
                            </tr> --}}
                        <tr
                            style="width:90%; text-align:left; color: white; font-size: 24px; font-weight: bold;">
                            <td style="vertical-align: middle; width: 50%;  padding-left: 220px; height:15%;">
                                {{ $user->phone == null ? 'Sin Dato' : $user->phone }} </td>
                            <td style="vertical-align: middle; width: 50%; padding-left: 90px; height:15%;">
                                {{ $user->email }}</td>
                        </tr>
                    </table>
                    <div class="condiciones"
                        style="width: 100%;  height: 60%; text-align: left; padding: 30px;">

                        <ul
                            style="padding-left: 65px;  color: white; font-size: 21px">
                            <p style="text-align: left; font-size: 20px;"> CONDICIONES:</p>
                            <span style="">-Condiciones de pago acordadas con el
                                vendedor</span>

                            <span style="white-space:pre-line">.Precios unitarios mostrados antes de IVA
                            </span>
                            <span>-Precios mostrados en pesos mexicanos (MXP)
                                {{-- {{ $quote->currency_type == 'USD' ? 'dolares (USD)' : 'pesos mexicanos (MXP)' }}. --}}
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

                    {{--  <div>
                        <p colspan="3" style="text-align: center;font-size: 27px;margin-bottom: 0">
                            www.trademarket.com.mx
                        </p>
                        <p colspan="3" style="text-align: center; font-size: 16px;  margin-bottom: 0">San Andrés
                            Atoto 155A
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
