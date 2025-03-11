<?php
session_start();
require_once 'conexion.php';

if (!isset($_POST['id_factura']) || !isset($_POST['tipo']) || !isset($_POST['id_responsable'])) {
    echo "<script>alert('Datos incompletos.'); window.history.back();</script>";
    exit();
}

$id_factura = $_POST['id_factura'];
$tipo = $_POST['tipo']; // Puede ser "cajas" o "unidades"
$id_responsable = $_POST['id_responsable']; // ID del responsable seleccionado

// Obtener detalles de la factura según el tipo seleccionado
if ($tipo == "cajas") {
    // Filtrar solo cajas
    $query = "SELECT f.factura, f.id_car, f.departamento, f.ciudad, i.id_item, i.nombre_item, i.Item, i.cajas as cantidad
              FROM t_facturas f
              JOIN t_items i ON f.factura = i.factura
              WHERE f.factura = ? AND i.cajas > 0";
} else {
    // Filtrar solo unidades
    $query = "SELECT f.factura, f.id_car, f.departamento, f.ciudad, i.id_item, i.nombre_item, i.Item, i.und as cantidad
              FROM t_facturas f
              JOIN t_items i ON f.factura = i.factura
              WHERE f.factura = ? AND i.und > 0";
}

$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $id_factura);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Administradores</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="Shortcut Icon" type="image/x-icon" href="assets/icons/icono.png" />
    <script src="js/sweet-alert.min.js"></script>
    <link rel="stylesheet" href="css/sweet-alert.css">
    <link rel="stylesheet" href="css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-1.11.2.min.js"><\/script>')</script>
    <script src="js/modernizr.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/main.js"></script>

        <style>

.biblioteca{


    margin-top: 5px;
    color: white;
    font-size: 35px;
}

</style>


</head>
<body>
    <div class="navbar-lateral full-reset">
        <div class="visible-xs font-movile-menu mobile-menu-button"></div>
        <div class="full-reset container-menu-movile custom-scroll-containers">
            <div class=" full-reset all-tittles">
                <i class="visible-xs zmdi zmdi-close pull-left mobile-menu-button" style="line-height: 55px; cursor: pointer; padding: 0 10px; margin-left: 7px;"></i> 
                 <div class="biblioteca">
                <center>AUDITORIAS PUYO</center>
             </div>
            </div>
            <div class="full-reset" style="background-color:#2B3D51; padding: 10px 0; color:#fff;">
                <figure>
                    <img src="assets/img/logo.png" alt="Biblioteca" class="img-responsive center-box" style="width:55%;">
                </figure>
                <p class="text-center" style="padding-top: 15px;">AUDITORIAS PUYO</p>
            </div>
            <div class="full-reset nav-lateral-list-menu">
                <ul class="list-unstyled">
                <li><a href="admin.php"><i class="zmdi zmdi-book zmdi-hc-fw"></i>&nbsp;&nbsp; Reporte Auditoria</a></li>
                
                
                    <li>
                    
                     
                        </ul>
                    </li>
                    
                    
                    
                    
                </ul>
            </div>
        </div>
    </div>
    <div class="content-page-container full-reset custom-scroll-containers">
        <nav class="navbar-user-top full-reset">
            <ul class="list-unstyled full-reset">
                <figure>
                   <img src="assets/img/user005.png" alt="user-picture" class="img-responsive img-circle center-box">
                </figure>
                <li style="color:#fff; cursor:default;">
                    <strong><?php echo $_SESSION['user'];?></strong>
                </li>
                <li  class="tooltips-general exit-system-button" data-href="index.php" data-placement="bottom" title="Salir del sistema">
                    <i class="zmdi zmdi-power"></i>
                </li>
            

                <li class="mobile-menu-button visible-xs" style="float: left !important;">
                    <i class="zmdi zmdi-menu"></i>
                </li>
                
              
            </ul>
        </nav>
        <div class="container">
         
                   
            <div class="col-xs-12 col-sm-12 col-md-12 text-justify lead">
                <hr>
                
                </div>
                <br>
                <h2>Auditar Factura - <?php echo ucfirst($tipo); ?></h2>
<form action="guardar_auditoria.php" method="POST">
    <input type="hidden" name="id_auditor" value="<?php echo $_SESSION['id_usuario']; ?>">
    <input type="hidden" name="id_factura" value="<?php echo $id_factura; ?>">
    <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
    <input type="hidden" name="id_responsable" value="<?php echo $id_responsable; ?>">

    <div class="form-group">
        <label>Responsable:</label>
        <input type="text" class="form-control" value="<?php
            // Obtener el nombre del responsable seleccionado
            $query_responsable = "SELECT responsable FROM t_responsables WHERE id_responsable = ?";
            $stmt_responsable = $conexion->prepare($query_responsable);
            $stmt_responsable->bind_param("i", $id_responsable);
            $stmt_responsable->execute();
            $result_responsable = $stmt_responsable->get_result();
            $row_responsable = $result_responsable->fetch_assoc();
            echo htmlspecialchars($row_responsable['responsable']);
        ?>" readonly>
    </div>

    <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Codigo</th>
                <th>Cantidad</th>
                <th>¿Correcto?</th>
                <th>Cantidad Real</th>
                <th>Descripción del Error</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nombre_item']); ?></td>
                    <td><?php echo htmlspecialchars($row['Item']); ?></td>
                    <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                    <td>
                        <select name="correcto_<?php echo $row['id_item']; ?>" class="form-control correcto" data-id="<?php echo $row['id_item']; ?>">
                            <option value="si" selected>Sí</option>
                            <option value="no">No</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="cantidad_correcta_<?php echo $row['id_item']; ?>" 
                               id="cantidad_<?php echo $row['id_item']; ?>" 
                               class="form-control cantidad" 
                               value="<?php echo $row['cantidad']; ?>" 
                               readonly>
                    </td>
                    <td>
                        <select name="descripcion_error_<?php echo $row['id_item']; ?>" class="form-control">
                            <option value="">Seleccione una opción</option>
                            <option value="mercancia faltante">Mercancía Faltante</option>
                            <option value="mercancia sobrante">Mercancía Sobrante</option>
                            <option value="mercancia cambiada">Mercancía Cambiada</option>
                        </select>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

    <button type="submit" class="btn btn-primary">Guardar Auditoría</button>
</form>

<!-- Script para habilitar/deshabilitar el campo de cantidad real -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".correcto").forEach(select => {
        select.addEventListener("change", function () {
            let id = this.getAttribute("data-id");

            // Depuración: Verificar si se obtiene un ID válido
            console.log("ID obtenido:", id);

            let cantidadInput = document.getElementById("cantidad_" + id);

            if (cantidadInput) {
                cantidadInput.readOnly = (this.value === "si");
            } else {
                console.error("No se encontró el input para el ID:", id);
            }
        });
    });
});

</script>

    </div>
</body>
</html>