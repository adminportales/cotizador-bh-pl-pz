<!DOCTYPE html>
<html>

<head>
    <title>Mi PDF Personalizado</title>
    <style>
        /* Estilo para el fondo de todas las páginas (excepto la primera y la última) */
        @page {
            background-image: url('<?php echo $data["fondo"]; ?>');
            background-repeat: no-repeat;
            background-size: cover;
        }

        /* Elimina el fondo en la primera y última página */
        @page :first {
            background-image: none;
        }

        @page :last {
            background-image: none;
        }

        /* Estilo para el encabezado */
        #page-header {
            text-align: center;
        }

        /* Estilo para el footer */
        #page-footer {
            text-align: center;
        }

        /* Estilo para la portada */
        .portada {
            width: 100%;
            height: auto;
        }

        /* Estilo para la sección de productos */
        .productos {
            margin-top: 20px;
        }

        /* Estilo para la información en forma de tabla */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
            padding: 8px;
        }
    </style>
</head>

<body>
    <!-- Encabezado con imagen -->
    <div id="page-header">
        <img src="<?php echo $data['encabezado']; ?>" alt="Encabezado">
    </div>

    <!-- Portada -->
    <div id="first-page-header">
        <img src="<?php echo $data['portada']; ?>" class="portada" alt="Portada">
    </div>

    <!-- Contenido del PDF -->
    <div id="content">
        <h1>Mi PDF Personalizado</h1>
        <p>Este es un ejemplo de un PDF personalizado con imágenes, encabezado, footer, sección de productos e
            información en forma de tabla.</p>

        <!-- Sección de productos (personalizada con variable) -->
        <?php if ($data['productos_por_pagina'] === 1 || $data['productos_por_pagina'] === 2): ?>
        <div class="productos">
            <!-- Puedes personalizar el contenido de productos aquí -->
            <p>Producto 1: Nombre, descripción, precio, etc.</p>
            <?php if ($data['productos_por_pagina'] === 2): ?>
            <p>Producto 2: Nombre, descripción, precio, etc.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Información en forma de tabla (personalizada con variable) -->
        <?php if ($data['mostrar_formato_de_tabla']): ?>
        <table>
            <thead>
                <tr>
                    <th>Encabezado de la tabla</th>
                    <th>Encabezado de la tabla</th>
                    <!-- Agrega más encabezados si es necesario -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Dato 1</td>
                    <td>Dato 2</td>
                    <!-- Agrega más datos si es necesario -->
                </tr>
                <!-- Agrega más filas de datos si es necesario -->
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Footer con imagen -->
    <div id="page-footer">
        <img src="<?php echo $data['pie_pagina']; ?>" alt="Footer">
    </div>
</body>

</html>
