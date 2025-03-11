<?php
require_once 'conexion.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $id_auditor = $_POST['id_auditor'];
    $id_responsable = $_POST['id_responsable'];
    $id_cargue = $_POST['id_cargue'];
    $categoria_auditoria = $_POST['categoria_auditoria'];
   
    // Validar si el auditor existe en la tabla `auditores`
    $checkAuditor = $conn->prepare("SELECT id_auditor FROM auditores WHERE id_auditor = ?");
    $checkAuditor->bind_param("i", $id_auditor);
    $checkAuditor->execute();
    $checkAuditor->store_result();

    if ($checkAuditor->num_rows === 0) {
        echo "<script>alert('Error: El auditor no existe.'); window.location.href='formulario.php';</script>";
        exit();
    }
    $checkAuditor->close();

    // Validar si el responsable existe en la tabla `responsables`
    $checkResponsable = $conn->prepare("SELECT id_responsable FROM responsables WHERE id_responsable = ?");
    $checkResponsable->bind_param("i", $id_responsable);
    $checkResponsable->execute();
    $checkResponsable->store_result();

    if ($checkResponsable->num_rows === 0) {
        echo "<script>alert('Error: El responsable no existe.'); window.location.href='formulario.php';</script>";
        exit();
    }
    $checkResponsable->close();

    // Validar si el cargue existe en la tabla `cargues`
    $checkCargue = $conn->prepare("SELECT id_cargue FROM cargues WHERE id_cargue = ?");
    $checkCargue->bind_param("i", $id_cargue);
    $checkCargue->execute();
    $checkCargue->store_result();

    if ($checkCargue->num_rows === 0) {
        echo "<script>alert('Error: El cargue no existe.'); window.location.href='formulario.php';</script>";
        exit();
    }
    $checkCargue->close();

    // Insertar la auditoría en la base de datos
    $stmt = $conn->prepare("INSERT INTO auditorias (id_auditor, id_responsable, id_cargue, categoria_auditoria, fecha_ingreso, ) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissi", $id_auditor, $id_responsable, $id_cargue, $categoria_auditoria, $fecha, );

    if ($stmt->execute()) {
        echo "<script>alert('Auditoría registrada exitosamente.'); window.location.href='lista_auditorias.php';</script>";
    } else {
        echo "<script>alert('Error al registrar la auditoría.'); window.location.href='formulario.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

