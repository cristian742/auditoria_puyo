<?php
session_start();
if (@!$_SESSION['user']) {
    header("Location:index.php");
}elseif ($_SESSION['rol']==2) {
    header("Location:iniciouser.php");
}



$id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario de la sesión

// Obtener datos para las listas desplegables
$query_facturas = "SELECT factura FROM t_facturas ORDER BY factura ASC";
$resultado_facturas = $conexion->query($query_facturas);

$query_responsables = "SELECT id_responsable, responsable FROM t_responsables ORDER BY responsable ASC";
$resultado_responsables = $conexion->query($query_responsables);

$query_items = "SELECT id_item, nombre_item FROM t_items ORDER BY nombre_item ASC";
$resultado_items = $conexion->query($query_items);

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $factura = $_POST['factura'];
    $id_responsable = $_POST['id_responsable'];
    $id_item = $_POST['id_item'];
    $error = $_POST['error'];
    $descripcion_error = $_POST['descripcion_error'];
    $tipo_error = $_POST['tipo_error'];
    $cantidad_error = $_POST['cantidad_error'];

    // Calcular el valor del error
    $query_item = "SELECT valor_und, EMB FROM t_items WHERE id_item = $id_item";
    $resultado_item = $conexion->query($query_item);
    $item = $resultado_item->fetch_assoc();

    if ($tipo_error == 'caja') {
        $valor_error = $cantidad_error * ($item['valor_und'] * $item['EMB']);
    } else {
        $valor_error = $cantidad_error * $item['valor_und'];
    }

    // Insertar el error en la base de datos
    $query = "INSERT INTO t_errores (factura, id_responsable, id_usuario, id_item, error, descripcion_error, tipo_error, cantidad_error, valor_error)
              VALUES ($factura, $id_responsable, $id_usuario, $id_item, '$error', '$descripcion_error', '$tipo_error', $cantidad_error, $valor_error)";

    if ($conexion->query($query) === TRUE) {
        echo "Error registrado correctamente.";
    } else {
        echo "Error al registrar: " . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Error</title>
</head>
<body>
    <h1>Registrar Error</h1>
    <p>Usuario: <?php echo $_SESSION['nombre_usuario']; ?></p>
    <form method="POST" action="">
        <!-- Lista desplegable para Factura -->
        <label for="factura">Factura:</label>
        <select name="factura" required>
            <?php while ($fila = $resultado_facturas->fetch_assoc()) { ?>
                <option value="<?php echo $fila['factura']; ?>">
                    <?php echo $fila['factura']; ?>
                </option>
            <?php } ?>
        </select><br>

        <!-- Lista desplegable para Responsable -->
        <label for="id_responsable">Responsable:</label>
        <select name="id_responsable" required>
            <?php while ($fila = $resultado_responsables->fetch_assoc()) { ?>
                <option value="<?php echo $fila['id_responsable']; ?>">
                    <?php echo $fila['responsable']; ?>
                </option>
            <?php } ?>
        </select><br>

        <!-- Lista desplegable para Ítem -->
        <label for="id_item">Ítem:</label>
        <select name="id_item" required>
            <?php while ($fila = $resultado_items->fetch_assoc()) { ?>
                <option value="<?php echo $fila['id_item']; ?>">
                    <?php echo $fila['nombre_item']; ?>
                </option>
            <?php } ?>
        </select><br>

        <!-- Campos restantes del formulario -->
        <label for="error">Error:</label>
        <input type="text" name="error" required><br>

        <label for="descripcion_error">Descripción del Error:</label>
        <textarea name="descripcion_error" required></textarea><br>

        <label for="tipo_error">Tipo de Error:</label>
        <select name="tipo_error" required>
            <option value="caja">Caja</option>
            <option value="unidad">Unidad</option>
        </select><br>

        <label for="cantidad_error">Cantidad con Error:</label>
        <input type="number" name="cantidad_error" required><br>

        <button type="submit">Registrar Error</button>
    </form>
</body>
</html>