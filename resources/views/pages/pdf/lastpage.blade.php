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
    </style>
</head>

<body>
    <!-- Imagen en la última página dentro de la etiqueta img -->
    <div id="last-page-img">
        <img src="{{ $data['portada'] }}" class="portada" alt="">
    </div>
</body>

</html>
