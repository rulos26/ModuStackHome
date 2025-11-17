<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Prueba PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .info {
            background-color: #e8f5e9;
            padding: 15px;
            border-left: 4px solid #4CAF50;
            margin: 20px 0;
        }
        .info-item {
            margin: 10px 0;
        }
        .success {
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Página de Prueba PHP</h1>
        
        <div class="info">
            <div class="info-item">
                <span class="success">✓ PHP está funcionando correctamente</span>
            </div>
            <div class="info-item">
                <strong>Versión de PHP:</strong> <?php echo phpversion(); ?>
            </div>
            <div class="info-item">
                <strong>Fecha y Hora del Servidor:</strong> <?php echo date('Y-m-d H:i:s'); ?>
            </div>
            <div class="info-item">
                <strong>Directorio Actual:</strong> <?php echo __DIR__; ?>
            </div>
            <div class="info-item">
                <strong>Archivo Actual:</strong> <?php echo __FILE__; ?>
            </div>
        </div>

        <h2>Información del Servidor</h2>
        <div class="info">
            <div class="info-item">
                <strong>Software del Servidor:</strong> <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'No disponible'; ?>
            </div>
            <div class="info-item">
                <strong>Método de Solicitud:</strong> <?php echo $_SERVER['REQUEST_METHOD'] ?? 'No disponible'; ?>
            </div>
            <div class="info-item">
                <strong>URL Actual:</strong> <?php echo $_SERVER['REQUEST_URI'] ?? 'No disponible'; ?>
            </div>
        </div>

        <h2>Prueba de Funcionalidad PHP</h2>
        <div class="info">
            <?php
            // Prueba de variables
            $mensaje = "¡PHP está funcionando!";
            $numero = 42;
            $fecha = date('d/m/Y');
            
            echo "<p><strong>Variable de texto:</strong> " . $mensaje . "</p>";
            echo "<p><strong>Variable numérica:</strong> " . $numero . "</p>";
            echo "<p><strong>Fecha formateada:</strong> " . $fecha . "</p>";
            
            // Prueba de array
            $colores = ['Rojo', 'Verde', 'Azul'];
            echo "<p><strong>Array de colores:</strong> " . implode(', ', $colores) . "</p>";
            ?>
        </div>
    </div>
</body>
</html>

