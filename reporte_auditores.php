<?php
session_start();
require_once 'conexion.php';
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'es');

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['user']) || $_SESSION['rol'] != 'admin') {
    header("Location: registrar_auditoria.php");
    exit();
}

// Obtener los parámetros de búsqueda
$busqueda_auditor = isset($_GET['busqueda_auditor']) ? $_GET['busqueda_auditor'] : '';
$mes = isset($_GET['mes']) ? $_GET['mes'] : '';
$anio = isset($_GET['anio']) ? $_GET['anio'] : '';

// Consulta SQL para obtener el número de auditorías por auditor y fecha
$query = "SELECT u.user AS nombre_auditor, 
                 DATE(e.fecha) AS fecha_auditoria, 
                 COUNT(e.id_error) AS total_auditorias
          FROM t_errores e
          JOIN t_usuarios u ON e.id_auditor = u.id_usuario
          WHERE u.user LIKE ?
          AND (MONTH(e.fecha) = ? OR ? = '')
          AND (YEAR(e.fecha) = ? OR ? = '')
          GROUP BY u.user, DATE(e.fecha)
          ORDER BY e.fecha DESC";

// Preparar la consulta
$stmt = $conexion->prepare($query);
$param_busqueda = "%$busqueda_auditor%"; // Agregar comodines para búsqueda parcial
$stmt->bind_param("siiii", $param_busqueda, $mes, $mes, $anio, $anio);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Reporte de Auditores</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="Shortcut Icon" type="image/x-icon" href="assets/icons/icono.png" />
    <script src="js/sweet-alert.min.js"></script>
    <link rel="stylesheet" href="css/sweet-alert.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
                color:rgb(39, 96, 158);
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
        <!-- Formulario de búsqueda por nombre del auditor y filtro por mes/año -->
        <div class="container mt-4">
        <div class="container mt-4">
        <h2 class="text-center mb-4">Reporte de Auditorías por Auditor</h2>

        <!-- Formulario de búsqueda por nombre del auditor y filtro por mes/año -->
        <form method="GET" action="reporte_auditores.php" class="search-form">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="busqueda_auditor" 
                           placeholder="Buscar por nombre del auditor..." 
                           value="<?php echo htmlspecialchars($busqueda_auditor); ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="mes">
                        <option value="">Seleccionar mes</option>
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($_GET['mes']) && $_GET['mes'] == $i) ? 'selected' : ''; ?>>
                                <?php echo strftime("%B", mktime(0, 0, 0, $i, 10)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="anio">
                        <option value="">Seleccionar año</option>
                        <?php for ($i = date("Y"); $i >= 2020; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($_GET['anio']) && $_GET['anio'] == $i) ? 'selected' : ''; ?>>
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </div>
        </form>

        <!-- Tabla de resultados -->
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>Auditor</th>
                        <th>Fecha</th>
                        <th>Total de Auditorías</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td data-label="Auditor"><?php echo htmlspecialchars($row['nombre_auditor']); ?></td>
                                <td data-label="Fecha"><?php echo strftime("%d de %B de %Y", strtotime($row['fecha_auditoria'])); ?></td>
                                <td data-label="Total de Auditorías"><?php echo htmlspecialchars($row['total_auditorias']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No se encontraron auditorías para este filtro.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>