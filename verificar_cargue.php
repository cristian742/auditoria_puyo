<?php
require_once 'conexion.php';

if (isset($_POST['id_car'])) {
    // Obtén el id_car desde la solicitud POST
    $id_car = $conexion->real_escape_string($_POST['id_car']);
    
    // Consulta para verificar el cargue
    $query = "SELECT * FROM t_facturas WHERE id_car = '$id_car'";
    $result = $conexion->query($query);
    
    // Imprimir información de depuración
    echo "Valor buscado: $id_car\n";
    echo "Número de filas encontradas: " . $result->num_rows . "\n";
    
    // Mostrar todos los valores de id_car en la base de datos
    $query_all = "SELECT DISTINCT id_car FROM t_facturas";
    $result_all = $conexion->query($query_all);
    
    echo "Valores de id_car en la base de datos:\n";
    while ($row = $result_all->fetch_assoc()) {
        echo $row['id_car'] . "\n";
    }
    
    // Respuesta para el cliente
    echo ($result->num_rows > 0) ? "exists" : "not_exists";
}
?>
