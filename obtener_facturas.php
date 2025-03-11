<?php
require_once 'conexion.php';

if (isset($_POST['id_car'])) {
    $id_car = $conexion->real_escape_string($_POST['id_car']);
    
    // Obtener facturas para este cargue
    $query = "SELECT DISTINCT factura FROM t_facturas WHERE id_car = '$id_car'";
    $result = $conexion->query($query);

    echo "<option value=''>Seleccione una factura</option>";
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['factura'] . "'>" . $row['factura'] . "</option>";
        }
    } else {
        echo "<option value=''>No hay facturas para este cargue</option>";
    }
}
?>