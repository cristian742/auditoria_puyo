<?php
session_start();
require_once 'conexion.php';

if (!isset($_GET['id_responsable'])) {
    echo "<script>alert('Responsable no seleccionado.'); window.history.back();</script>";
    exit();
}

$id_responsable = $_GET['id_responsable'];

// Obtener el nombre del responsable
$query_responsable = "SELECT responsable FROM t_responsables WHERE id_responsable = ?";
$stmt_responsable = $conexion->prepare($query_responsable);
$stmt_responsable->bind_param("i", $id_responsable);
$stmt_responsable->execute();
$result_responsable = $stmt_responsable->get_result();
$row_responsable = $result_responsable->fetch_assoc();
$responsable = $row_responsable['responsable'];

// Obtener los errores del responsable
$query_errores = "SELECT e.factura, e.descripcion_error, e.cantidad_error, e.tipo_error, e.costo_error, e.fecha, i.nombre_item, u.user AS nombre_auditor
                  FROM t_errores e
                  JOIN t_items i ON e.id_item = i.id_item
                  JOIN t_usuarios u ON e.id_auditor = u.id_usuario
                  WHERE e.id_responsable = ?";
$stmt_errores = $conexion->prepare($query_errores);
$stmt_errores->bind_param("i", $id_responsable);
$stmt_errores->execute();
$result_errores = $stmt_errores->get_result();
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
<style>
        /* Estilos para la tabla */
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table-custom thead th {
            background-color:rgb(39, 96, 158);
            color: white;
            font-weight: bold;
            padding: 12px;
            text-align: center;
        }

        .table-custom tbody td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        .table-custom tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Estilos para el botón de volver */
        .btn-volver {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-volver:hover {
            background-color: #0056b3;
        }

        /* Estilos para dispositivos móviles */
        @media (max-width: 768px) {
            .table-custom thead {
                display: none;
            }

            .table-custom tbody tr {
                display: block;
                margin-bottom: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }

            .table-custom tbody td {
                display: block;
                text-align: right;
                padding: 8px;
                border-bottom: none;
            }

            .table-custom tbody td::before {
                content: attr(data-label);
                float: left;
                font-weight: bold;
                text-transform: uppercase;
                color: #007bff;
            }
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
                    <li><a href="costo_errores.php"><i class="zmdi zmdi-money zmdi-hc-fw"></i>&nbsp;&nbsp; Costo Total de Errores</a></li>
                    <li><a href="reporte_auditores.php"><i class="zmdi zmdi-accounts"></i>&nbsp;&nbsp; Reporte Auditores</a></li>
                
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
                <div class="container mt-4">
        <h2 class="text-center mb-4">Detalles de Errores - <?php echo htmlspecialchars($responsable); ?></h2>

        <!-- Tabla de errores -->
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>Producto</th>
                        <th>Descripción del Error</th>
                        <th>Cantidad Errónea</th>
                        <th>Tipo de Error</th>
                        <th>Costo del Error</th>
                        <th>Fecha</th>
                        <th>Auditor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_errores->num_rows > 0): ?>
                        <?php while ($row = $result_errores->fetch_assoc()): ?>
                            <tr>
                                <td data-label="Factura"><?php echo htmlspecialchars($row['factura']); ?></td>
                                <td data-label="Producto"><?php echo htmlspecialchars($row['nombre_item']); ?></td>
                                <td data-label="Descripción del Error"><?php echo htmlspecialchars($row['descripcion_error']); ?></td>
                                <td data-label="Cantidad Errónea"><?php echo htmlspecialchars($row['cantidad_error']); ?></td>
                                <td data-label="Tipo de Error"><?php echo htmlspecialchars($row['tipo_error']); ?></td>
                                <td data-label="Costo del Error">$<?php echo number_format($row['costo_error'], 2); ?></td>
                                <td data-label="Fecha"><?php echo htmlspecialchars($row['fecha']); ?></td>
                                <td data-label="Auditor"><?php echo htmlspecialchars($row['nombre_auditor']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No hay errores registrados para este responsable.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Botón para volver al reporte de errores -->
        <a href="reporte_errores.php" class="btn btn-primary">Volver al Reporte</a>
    </div>
</body>
</html>