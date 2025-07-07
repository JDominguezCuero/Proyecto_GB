<?php
// PROYECTO_GB/Vista/Cliente/Certificaciones.php

// Asegúrate de que estas variables estén disponibles desde el controlador
$datos_cliente = $datos_cliente ?? [];
$producto_para_certificado = $producto_para_certificado ?? [];
$error_message = $error_message ?? '';

// Formatear la fecha actual para el certificado
$fecha_emision = date("d/m/Y");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Certificado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 960px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Estilos para los formularios y botones */
        .form-container {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            max-width: 750px; /* Alineado con el certificado */
            margin: 20px auto; /* Centrar */
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="date"],
        .form-group input[type="password"],
        .form-group select {
            width: calc(100% - 22px); /* Ajuste para padding y borde */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
            box-sizing: border-box; /* Incluir padding y border en el width */
        }

        .btn-primary {
            background-color: #ff9800; /* Naranja */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #e68900; /* Naranja más oscuro */
        }

        .message-box {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
            max-width: 750px; /* Alineado con el certificado */
            margin: 20px auto; /* Centrar */
        }

        .message-box.error {
            background-color: #ffebee;
            color: #d32f2f;
            border: 1px solid #d32f2f;
        }

        .message-box.success {
            background-color: #e8f5e9;
            color: #388e3c;
            border: 1px solid #388e3c;
        }

        .message-box.info {
            background-color: #e3f2fd;
            color: #1976d2;
            border: 1px solid #1976d2;
        }

        /* Estilos del certificado - AHORA CON ESTILOS MÍNIMOS ENCABEZADOS */
        #certificado-content { 
            border: 2px solid #f9be59; /* Un borde simple para que sepamos que algo se renderiza */
            padding: 50px; /* Padding original para espacio */
            margin: 30px auto; /* Margen y centrado */
            max-width: 750px; /* Ancho máximo */
            text-align: center;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 10px; /* Bordes redondeados simples */
            font-family: 'Times New Roman', serif; /* Fuente original */
            line-height: 1.8; /* Espaciado de línea original */
            position: relative; /* Mantener para otros posibles usos, pero sin conflicto */

            /* ¡¡¡ASEGÚRATE DE QUE NO HAYA NINGUNA DE ESTAS PROPIEDADES AQUÍ!!! */
            /* overflow: hidden; */
            /* Y NINGUNA REGLA PARA ::before o ::after */
        }

        /* Estilos para el contenido dentro del certificado */
        .certificate-header {
            margin-bottom: 30px;
        }
        .certificate-logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
        .bank-name {
            font-size: 2.5em;
            margin-bottom: 5px;
            color: #2e7d32; /* Verde */
        }
        .certificate-title {
            font-size: 2em;
            margin-bottom: 30px;
            color: #ff9800; /* Naranja */
        }
        .certificate-body p {
            margin-bottom: 15px;
            font-size: 1.1em;
        }
        .client-name {
            font-size: 1.6em;
            color: #2e7d32;
            margin: 20px 0;
        }
        .certificate-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .signature-line {
            width: 200px;
            height: 2px;
            background-color: #333;
            margin: 0 auto 10px auto;
        }
        .signature-text {
            font-style: italic;
            margin-bottom: 20px;
        }
        .emission-date, .bank-address {
            font-size: 0.9em;
            color: #666;
        }

        /* Media Queries para responsividad (parte del certificado) */
        @media (max-width: 768px) {
            #certificado-content { 
                padding: 20px;
            }
            .certificate-header .bank-name {
                font-size: 2em;
            }
            .certificate-title {
                font-size: 1.5em;
            }
        }
    </style>
    </head>
<body>

<h2 style="color: #2e7d32; text-align: center; margin-top: 20px;">Generar Certificado de Producto</h2>

<div class="form-container search-form"> 
    <form action="index.php" method="GET">
        <input type="hidden" name="controller" value="cliente">
        <input type="hidden" name="action" value="mostrarCertificaciones">
        <div class="form-group">
            <label for="N_Documento_Cliente">Número de Documento del Cliente:</label>
            <input type="text" id="N_Documento_Cliente" name="N_Documento_Cliente" value="<?php echo htmlspecialchars($_GET['N_Documento_Cliente'] ?? ''); ?>" required>
        </div>
        <button type="submit" class="btn-primary">Buscar Cliente y Generar Certificado</button>
    </form>
</div>

<?php if (!empty($error_message)): ?>
    <div class="message-box error" style="margin-top: 20px;">
        <p><?php echo htmlspecialchars($error_message); ?></p>
    </div>
<?php endif; ?>

<?php if (!empty($datos_cliente) && !empty($producto_para_certificado)): ?>
    <div id="certificado-content" class="certificate-container">
        <div class="certificate-header">
            <h1 class="bank-name">BANCO FINAN-CIAS</h1>
            <h2 class="certificate-title">Certificado de Producto</h2>
        </div>

        <div class="certificate-body">
            <p>El presente certificado hace constar que:</p>
            <p class="client-name"><strong><?php echo htmlspecialchars($datos_cliente['Nombre_Cliente'] ?? 'N/A') . ' ' . htmlspecialchars($datos_cliente['Apellido_Cliente'] ?? 'N/A'); ?></strong></p>
            <p>Con número de documento: <strong><?php echo htmlspecialchars($datos_cliente['N_Documento_Cliente'] ?? 'N/A'); ?></strong></p>
            <p>Es titular del producto: <strong><?php echo htmlspecialchars($producto_para_certificado['Nombre_Producto'] ?? 'N/A'); ?></strong></p>
            <p>Descripción del producto: <em><?php echo htmlspecialchars($producto_para_certificado['Descripcion_Producto'] ?? 'N/A'); ?></em></p>
            <p>Hace parte de los servicios financieros ofrecidos por esta entidad.</p>
        </div>

        <div class="certificate-footer">
            <p class="emission-date">Adquirido el: <strong><?php echo htmlspecialchars($fecha_emision); ?></strong></p>
            <p class="bank-address">Fecha de emisión del certificado: <?php echo htmlspecialchars($fecha_emision); ?></p>
            <p class="bank-address">BANCO FINAN-CIAS - Puerto Boyacá, Boyacá, Colombia</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button id="download-pdf" class="btn-primary">Descargar Certificado PDF</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() { 
            const downloadButton = document.getElementById('download-pdf');
            if (downloadButton) { 
                downloadButton.addEventListener('click', function() {
                    const element = document.getElementById('certificado-content');
                    if (element) {
                        setTimeout(() => { 
                            html2pdf().from(element).set({
                                margin: [10, 10, 10, 10], 
                                filename: 'Certificado_Cliente_<?php echo htmlspecialchars($datos_cliente['N_Documento_Cliente'] ?? 'documento'); ?>.pdf',
                                image: { type: 'jpeg', quality: 0.98 },
                                html2canvas: { 
                                    scale: 3, 
                                    logging: true, // Mantenemos en true para ver cualquier log
                                    dpi: 300, 
                                    letterRendering: true, 
                                    useCORS: true,
                                },
                                jsPDF: { 
                                    unit: 'mm', 
                                    format: 'a4', 
                                    orientation: 'portrait' 
                                },
                                pagebreak: { 
                                    mode: 'avoid-all' 
                                }
                            }).save();
                        }, 500); // 500 milisegundos de retraso
                    } else {
                        console.error('Elemento #certificado-content no encontrado para la descarga.');
                    }
                });
            }
        });
    </script>   

<?php elseif (empty($_GET['N_Documento_Cliente']) && empty($error_message)): ?>
    <div class="message-box info">
        <p>Por favor, use el campo de búsqueda para generar el certificado de un cliente.</p>
    </div>
<?php endif; ?>

</body>
</html>