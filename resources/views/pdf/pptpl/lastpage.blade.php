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

        /*    .body {
            height: 19cm;
            margin: 1cm 1cm 1cm 1cm;
            margin-top: -20.5cm;
        } */

        .body {
            /*    height: 100vh; */
            background-image: url(quotesheet/pl/ppt/PLCONTRAPORTADA.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center left;
        }
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
            <div class="content" style= "height: 100%;">
                <div class="contain" style=" widht:100%; height:95%;">
                    <table class="info-seller" style= "widht:100%;">
                        <tr style="">
                            {{--  <td
                            style="background-color: orange;  height: 80%;
                        text-align: center;
                        vertical-align: middle;">
                            <p style="font-size:20px;  font-weight: bold">
                                {{ $user->name }}
                            </p>
                        </td> --}}

                            <td
                                style="text-align: left; height: 50%;   letter-spacing: 2px; width: 50%; vertical-align: bottom; padding:10mm; padding-left: 3.3cm; margin: -1cm; color: white; font-size: 22px">

                                <p style="padding-bottom: 12px;">
                                    <strong> {{ $user->phone == null ? 'Sin Dato' : $user->phone }}
                                    </strong>
                                </p>
                                <p style="margin: 1px;">
                                    {{ $user->email }}
                                </p>

                            </td>
                            <td style="width: 50%; height: 100%; margin-top: 60px;">

                                <div class="condiciones"
                                    style="text-align: left; width: 85%; height: 80%; margin: 20px; padding: 20px; color: white; font-size: 19px; font-weight:bold">

                                    <ul style="margin-top: 10%">
                                        <p style="text-align: left; font-size: 22px;"> CONDICIONES:</p>
                                        <span style="">.Condiciones de pago acordadas con el
                                            vendedor</span>

                                        <span style="white-space:pre-line">.Precios unitarios mostrados antes de IVA
                                        </span>
                                        <span>.Precios mostrados en
                                            {{ $quote->currency_type == 'USD' ? 'dolares (USD)' : 'pesos mexicanos (MXP)' }}.
                                        </span>
                                        <br>
                                        <span style="white-space: normal">.El importe cotizado corresponde a la cantidad
                                            de
                                            piezas y número de
                                            tintas
                                            arriba
                                            mencionadas, si se
                                            modifica
                                            el número de piezas el precio cambiaría.</span>
                                        <br>
                                        <span>.El tiempo de entrega empieza a correr una vez recibida la Orden de Compra
                                            y
                                            autorizada la muestra física
                                            o
                                            virtual a solicitud del cliente.</span>

                                        <span style="white-space:normal">
                                            .Vigencia de la cotización
                                            {{ $quote->latestQuotesUpdate->quotesInformation->shelf_life ?: 5 }}
                                            días
                                            {{ $quote->type_days == 0 ? 'hábiles' : 'naturales' }}.
                                        </span>
                                        <br>
                                        <span>.Producto cotizado de fabricación nacional o importación puede afinarse
                                            la
                                            fecha de
                                            entrega previo a la
                                            emisión
                                            de Orden de Compra.</span>
                                        <br>
                                        <span>.Producto cotizado disponible en stock a la fecha de esta cotización
                                            puede
                                            modificarse al paso de los
                                            días
                                            sin
                                            previo aviso. Solo se bloquea el inventario al recibir Orden de
                                            Compra</span>
                                    </ul>
                                </div>

                            </td>

                        </tr>


                    </table>
                </div>
            </div>
        </div>
    @endif
</body>

</html>
