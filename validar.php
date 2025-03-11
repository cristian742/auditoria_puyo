<?php
session_start();
require_once 'conexion.php';

$email = $_POST['mail'];
$password = $_POST['pass'];

// Verificar si el usuario es administrador
$sql_admin = "SELECT id_usuario, user, rol FROM t_usuarios WHERE email = ? AND pasadmin = ?";
$stmt_admin = $conexion->prepare($sql_admin);
$stmt_admin->bind_param("ss", $email, $password);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();

if ($result_admin->num_rows > 0) {
    $row = $result_admin->fetch_assoc();
    $_SESSION['id_usuario'] = $row['id_usuario'];
    $_SESSION['user'] = $row['user'];
    $_SESSION['rol'] = 'admin';
    header("Location: admin.php");
    exit();
}

// Verificar si el usuario es auditor
$sql_auditor = "SELECT id_usuario, user, rol FROM t_usuarios WHERE email = ? AND password = ?";
$stmt_auditor = $conexion->prepare($sql_auditor);
$stmt_auditor->bind_param("ss", $email, $password);
$stmt_auditor->execute();
$result_auditor = $stmt_auditor->get_result();

if ($result_auditor->num_rows > 0) {
    $row = $result_auditor->fetch_assoc();
    $_SESSION['id_usuario'] = $row['id_usuario'];
    $_SESSION['user'] = $row['user'];
    $_SESSION['rol'] = 'auditor';
    header("Location: registrar_auditoria.php");
    exit();
}

echo "<script>alert('Credenciales incorrectas.'); window.location.href='index.php';</script>";
?>