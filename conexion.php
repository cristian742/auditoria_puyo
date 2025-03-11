
<?php
// conexion.php
$host = "localhost:3306"; // Servidor de la base de datos
$usuario = "root";   // Usuario de la base de datos
$contrasena = "";    // Contraseña de la base de datos
$base_datos = "nueva"; // Nombre de la base de datos

// Crear conexión
$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>
