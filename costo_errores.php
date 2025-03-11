<?php
session_start();
require_once 'conexion.php';
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'es');

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['user']) || $_SESSION['rol'] != 'admin') {
    header("Location: registrar_auditoria.php");
    exit();
}

// Obtener los parámetros de filtro (mes y día)
$mes = isset($_GET['mes']) ? $_GET['mes'] : '';
$dia = isset($_GET['dia']) ? $_GET['dia'] : '';

// Consulta SQL para calcular el costo total de los errores filtrados por mes y día
$query = "SELECT SUM(costo_error) AS costo_total 
          FROM t_errores 
          WHERE (MONTH(fecha) = ? OR ? = '') 
          AND (DAY(fecha) = ? OR ? = '')";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ssss", $mes, $mes, $dia, $dia);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$costo_total = $row['costo_total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
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

    <!-- Bootstrap CSS -->
    <!-- Estilos personalizados -->
    <style>
        .container {
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(233, 217, 217, 0.1);
            border-radius: 8px;
        }
        .card-header {
            background-color:rgb(39, 96, 158);
            color: white;
            font-weight: bold;
        }
        .btn-volver {
            margin-top: 20px;
            background-color:rgb(39, 96, 158);
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
        <!-- Formulario de filtro por mes y día -->
        <form method="GET" action="costo_errores.php" class="search-form">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" name="mes">
                        <option value="">Seleccionar mes</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo ($mes == $i) ? 'selected' : ''; ?>>
                                <?php echo strftime("%B", mktime(0, 0, 0, $i, 10)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" name="dia">
                        <option value="">Seleccionar día</option>
                        <?php for ($i = 1; $i <= 31; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo ($dia == $i) ? 'selected' : ''; ?>>
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Tarjeta para mostrar el costo total -->
        <div class="card">
            <div class="card-header text-center">
                <h2>Costo Total de Errores</h2>
            </div>
            <div class="card-body text-center">
                <h2>$<?php echo number_format($costo_total, 2); ?></h2>
                <p>
                    <?php if ($mes && $dia): ?>
                        Costo total para el día <?php echo $dia; ?> del mes <?php echo strftime("%B", mktime(0, 0, 0, $mes, 10)); ?>.
                    <?php elseif ($mes): ?>
                        Costo total para el mes <?php echo strftime("%B", mktime(0, 0, 0, $mes, 10)); ?>.
                    <?php elseif ($dia): ?>
                        Costo total para el día <?php echo $dia; ?> de todos los meses.
                    <?php else: ?>
                        Costo total acumulado de todos los errores registrados hasta el momento.
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Botón para volver al reporte de errores -->
        <a href="reporte_errores.php" class="btn btn-volver">Volver al Reporte</a>
    </div>

</body>
</html>