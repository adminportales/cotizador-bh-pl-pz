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

        #first-page-img {
            width: 100vh;
            height: 100vh;
        }

        .body {
            padding: 2cm 2cm 2cm 2cm;
        }

        /* Logos */
        .logos {
            width: 100%;
            height: 120px;
            text-align: center;
        }

        .logo {
            width: auto;
            height: 100%;
            object-fit: cover;
        }

        /* Informacion */
        .client {
            margin-top: 35px;
            font-size: 30px;
            font-weight: bold;
        }

        .client p {
            margin: 0;
            text-align: center;
        }

        .client .name-customer{
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
        {{-- Esta portada va el logo, nombre del vendedor y cliente --}}
        <div class="body">
            <div class="logos">
                <img src="quotesheet/bh/logo.png" class="logo">
                {{-- <img src="quotesheet/bh/logo.png" class="logo"> --}}
            </div>
            <div class="client">
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
                @endif
            </div>
            <div class="fecha">
                <p>Fecha Cotización: {{ $quote->created_at->format('d/m/Y') }}</p>
            </div>
        </div>
    @endif
</body>

</html>
