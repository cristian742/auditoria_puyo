<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['user']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit();
}
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
                    <li><a href="reporte_errores.php"><i class="zmdi zmdi-assignment-o zmdi-hc-fw"></i>&nbsp;&nbsp; Reporte Errores</a></li>
                
                
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
            <h2 class="text-center">Registrar Auditoría</h2>
<!-- Formulario para seleccionar cargue, factura, tipo y responsable -->
<!-- Formulario para seleccionar cargue, factura, tipo y responsable -->
<form action="auditar_factura.php" method="POST">
    <!-- Selección de Cargue -->
    <div class="form-group">
    <label for="id_car">Seleccionar Cargue:</label>
<input list="cargues" id="id_car" name="id_car" class="form-control" required>
<datalist id="cargues">
            <?php
            // Obtener los cargues únicos de la tabla t_facturas
            $query = "SELECT DISTINCT id_car FROM t_facturas";
            $result = $conexion->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id_car'] . "'>" . $row['id_car'] . "</option>";
                }
            } else {
                echo "<option value=''>No hay cargues disponibles</option>";
            }
            ?>
        </datalist>
    </div>

    <!-- Selección de Factura -->
    <div class="form-group">
        <label for="factura">Seleccionar Factura:</label>
        <select class="form-control" id="factura" name="id_factura" required>
            <option value="">Seleccione una factura</option>
        </select>
    </div>

    <!-- Selección de Tipo (Cajas o Unidades) -->
    <div class="form-group">
        <label for="tipo">Seleccionar Tipo:</label>
        <select class="form-control" id="tipo" name="tipo" required>
            <option value="">Seleccione un tipo</option>
            <option value="cajas">Cajas</option>
            <option value="unidades">Unidades</option>
        </select>
    </div>

    <!-- Selección de Responsable -->
    <div class="form-group">
        <label for="id_responsable">Seleccionar Responsable:</label>
        <select class="form-control" id="id_responsable" name="id_responsable" required>
            <option value="">Seleccione un responsable</option>
            <?php
            // Obtener la lista de responsables desde la base de datos
            $query_responsables = "SELECT id_responsable, responsable FROM t_responsables";
            $result_responsables = $conexion->query($query_responsables);
            while ($responsable = $result_responsables->fetch_assoc()) {
                echo "<option value='" . $responsable['id_responsable'] . "'>" . $responsable['responsable'] . "</option>";
            }
            ?>
        </select>
    </div>

    <br>
    <button type="submit" class="btn btn-primary">Generar Auditoría</button>
</form>
        </div>
    </div>

    <!-- Script para cargar facturas dinámicamente -->
    <script>
        $(document).ready(function() {
            $("#id_car").change(function() {
                var idCar = $(this).val();

                if (idCar) {
                    $.ajax({
                        url: "obtener_facturas.php",
                        type: "POST",
                        data: { id_car: idCar },
                        success: function(data) {
                            $("#factura").html(data);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error en la solicitud AJAX:", error);
                        }
                    });
                } else {
                    $("#factura").html('<option value="">Seleccione un cargue</option>');
                }
            });
        });
    </script>
</body>
</html>