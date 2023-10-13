<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cotizacion BH</title>
    <style>
        @page {
            margin: 2cm;
            margin-right: 2cm;
            margin-top: 2cm;
            margin-bottom: 2cm;
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

        .footer {
            width: 100%;
            text-align: center;
        }

        .contact {
            font-size: 30px;
            font-weight: bold;
        }
        .info-seller{
            width: 100%;
            margin-top: 20px;
            font-size: 20px;

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
        <div class="contact">
            Contacto
        </div>
        <table class="info-seller">
            <tr>
                <td colspan="2">{{ $user->name }}</td>
            </tr>
            <tr>
                <td style="vertical-align: middle"> <img src="quotesheet/bh/icon-whatsapp.png" alt=""> {{ $user->phone == null ? 'Sin Dato' : $user->phone }} </td>
                <td style="vertical-align: middle"><img src="quotesheet/bh/icon-email.png" alt=""> {{ $user->email }}</td>
            </tr>
        </table>
        <table class="footer">
            <tr>
                <td colspan="3" style="text-align: center;font-size: 27px;">www.trademarket.com.mx</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; font-size: 16px;">San Andrés Atoto 155A Naucalpan de
                    Juárez, Méx. C.P. 53550
                    Tel. +52(55) 5290 9100</td>
            </tr>
        </table>
    @endif
</body>

</html>
