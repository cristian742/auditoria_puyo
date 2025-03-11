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

// Filtros
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : '';

// Consulta base
$query = "SELECT e.fecha, SUM(e.costo_error) AS costo_total
          FROM t_errores e
          WHERE e.id_responsable = ?";

// Aplicar filtros
if ($filtro == 'dia' && !empty($fecha)) {
    $query .= " AND DATE(e.fecha) = ?";
} elseif ($filtro == 'mes' && !empty($fecha)) {
    $query .= " AND DATE_FORMAT(e.fecha, '%Y-%m') = ?";
} elseif ($filtro == 'año' && !empty($fecha)) {
    $query .= " AND YEAR(e.fecha) = ?";
}

$query .= " GROUP BY e.fecha";

$stmt = $conexion->prepare($query);

if ($filtro == 'dia' && !empty($fecha)) {
    $stmt->bind_param("is", $id_responsable, $fecha);
} elseif ($filtro == 'mes' && !empty($fecha)) {
    $stmt->bind_param("is", $id_responsable, $fecha);
} elseif ($filtro == 'año' && !empty($fecha)) {
    $stmt->bind_param("is", $id_responsable, $fecha);
} else {
    $stmt->bind_param("i", $id_responsable);
}

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

        /* Estilos para el formulario de búsqueda */
        .search-form {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .search-form .form-control {
            border-radius: 4px;
        }

        .search-form .btn-primary {
            border-radius: 4px;
            padding: 10px 20px;
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
        <h2>Costo Total de Errores - <?php echo htmlspecialchars($responsable); ?></h2>

        <!-- Filtros -->
        <form method="GET" action="costo_total.php">
            <input type="hidden" name="id_responsable" value="<?php echo $id_responsable; ?>">
            <div class="form-group">
                <label for="filtro">Filtrar por:</label>
                <select class="form-control" id="filtro" name="filtro">
                    <option value="todos" <?php echo ($filtro == 'todos') ? 'selected' : ''; ?>>Todos</option>
                    <option value="dia" <?php echo ($filtro == 'dia') ? 'selected' : ''; ?>>Día</option>
                    <option value="mes" <?php echo ($filtro == 'mes') ? 'selected' : ''; ?>>Mes</option>
                    <option value="año" <?php echo ($filtro == 'año') ? 'selected' : ''; ?>>Año</option>
                </select>
            </div>
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Aplicar Filtro</button>
            <hr>
        </form>
        <div class="container mt-4">
        <h2 class="text-center mb-4">Reporte de Costos</h2>

        <!-- Tabla de costos -->
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Costo Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td data-label="Fecha"><?php echo htmlspecialchars($row['fecha']); ?></td>
                                <td data-label="Costo Total">$<?php echo number_format($row['costo_total'], 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">No hay datos disponibles.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>