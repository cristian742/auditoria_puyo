<?php
session_start();
require_once 'conexion.php';

// Configurar zona horaria de Colombia
date_default_timezone_set('America/Bogota');

// Habilitar reporte de errores completo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Obtener fecha actual en formato DATE
$fecha_actual = date('Y-m-d H:i:s');

// Verificar conexión a la base de datos
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar datos necesarios
if (empty($_POST)) {
    echo "<script>alert('No se enviaron datos.'); window.history.back();</script>";
    exit();
}

// Validar campos requeridos
$campos_requeridos = ['id_factura', 'tipo', 'id_responsable', 'id_auditor'];
foreach ($campos_requeridos as $campo) {
    if (!isset($_POST[$campo])) {
        echo "<script>alert('Falta el campo: $campo'); window.history.back();</script>";
        exit();
    }
}

$id_factura = (int)$_POST['id_factura'];
$tipo = $_POST['tipo'];
$id_responsable = (int)$_POST['id_responsable'];
$id_auditor = (int)$_POST['id_auditor'];

// Variable para contar errores insertados
$errores_insertados = 0;

foreach ($_POST as $clave => $valor) {
    if (strpos($clave, 'correcto_') === 0) {
        $id_item = (int)str_replace('correcto_', '', $clave);
        
        // Solo procesar si no es correcto
        if ($valor === 'no') {
            $cantidad_correcta = (int)$_POST["cantidad_correcta_$id_item"];
            $descripcion_error = $_POST["descripcion_error_$id_item"];

            // Obtener información del ítem para calcular costo
            $consulta_item = "SELECT valor_und, emb FROM t_items WHERE id_item = ?";
            $stmt_item = $conexion->prepare($consulta_item);
            $stmt_item->bind_param("i", $id_item);
            $stmt_item->execute();
            $resultado_item = $stmt_item->get_result();
            
            if ($resultado_item && $resultado_item->num_rows > 0) {
                $fila_item = $resultado_item->fetch_assoc();
                $valor_und = (float)$fila_item['valor_und'];
                $emb = (int)$fila_item['emb'];

                // Calcular costo del error
                $costo_error = ($tipo === "unidades") 
                    ? $cantidad_correcta * $valor_und 
                    : $emb * $valor_und * $cantidad_correcta;

                // Preparar consulta de inserción
                $consulta = "INSERT INTO t_errores (
                    factura, id_responsable, id_item, 
                    descripcion_error, tipo_error, 
                    cantidad_error, costo_error, fecha, 
                    id_auditor
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $conexion->prepare($consulta);
                $stmt->bind_param(
                    "iiissidsi", // Corregido: "s" para fecha
                $id_factura, $id_responsable, $id_item, 
                $descripcion_error, $tipo, 
                 $cantidad_correcta, $costo_error, $fecha_actual, 
                 $id_auditor
                );

                if ($stmt->execute()) {
                    $errores_insertados++;
                } else {
                    echo "Error al insertar: " . $stmt->error;
                }
            }
        }
    }
}

// Mensaje de confirmación
if ($errores_insertados > 0) {
    echo "<script>
        alert('Se guardaron $errores_insertados errores en la auditoría.');
        window.location.href='reporte_errores.php';
    </script>";
} else {
    echo "<script>
        alert('¡¡Bien echo no se Encontraron errores¡¡ .');
        window.location.href='reporte_errores.php';
    </script>";
}
?>