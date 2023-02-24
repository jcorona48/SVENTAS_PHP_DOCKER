<?php
if (!isset($_SESSION))
    session_start();
$_SESSION['URL'] = "./notasest.php";
//echo '<pre>';
//	var_dump($_SESSION['estudiantes']);
//  echo '</pre>';
// verificando la autenticación de usuario
if (!isset($_SESSION['username']))
    header('Location: ./login.php');
if (!isset($_SESSION['estudiantes'])) {
    $_SESSION['estudiantes'] = [];
    $_SESSION['asignaturas'] = [];
    $_SESSION['PPs'] = [];
    $_SESSION['SPs'] = [];
    $_SESSION['TIs'] = [];
    $_SESSION['TPs'] = [];
    $_SESSION['EFs'] = [];
}
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'create':
            array_push($_SESSION['estudiantes'], $_POST['estudiante']);
            array_push($_SESSION['asignaturas'], $_POST['asignatura']);
            array_push($_SESSION['PPs'], $_POST['PP']);
            array_push($_SESSION['SPs'], $_POST['SP']);
            array_push($_SESSION['TIs'], $_POST['TI']);
            array_push($_SESSION['TPs'], $_POST['TP']);
            array_push($_SESSION['EFs'], $_POST['EF']);
            header('Location: ./login.php');
            break;
        case 'completed':
            $id = $_POST['id'];
            unset($_SESSION['estudiantes'][$id]);
            unset($_SESSION['asignaturas'][$id]);
            unset($_SESSION['PPs'][$id]);
            unset($_SESSION['SPs'][$id]);
            unset($_SESSION['TIs'][$id]);
            unset($_SESSION['TPs'][$id]);
            unset($_SESSION['EFs'][$id]);
            header('Location: ./login.php');
            break;
        case 'logout':
            session_destroy();
            // unset($_SESSION['username']);
            header('Location: ./login.php');
            break;
        default:
            header('Location: ./login.php');
            break;
    }
}
$class_bg = 'bg-success';
$cant_elementos = count($_SESSION['estudiantes']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Calificación de estudiantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        .header {
            padding: 30px;
        }
        /*.form {
			display: block;
    		margin: 0 auto;
    		text-align: center;
    	}*/
        .card-footer p {
            font-size: 11px;
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
    <div class="bg-dark header">
        <ul class="nav nav-tabs justify-content-center">
            <li class="nav-item ">
				<a class="nav-link text-white" aria-current="page" href="./index.php">HOME</a>
			</li>
            <li class="nav-item text-white">
                <a class="nav-link text-white " aria-current="page" href="./nomina.php">Nomina</a>
            </li>
            <li class="nav-item ">
                <a class="nav-link active" aria-current="page" href="./notasest.php">Notas de Estudiantes</a>
            </li>
            <li class="nav-item ">
                <form action="./notasest.php" method="POST">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" class="nav-link text-white">Cerrar Sesión</button>
                </form>
            </li>
        </ul>
    </div>
    <div class="header container-fluid bg-dark text-white text-center mb-5">
        <h2>Control de Calificaciones</h2>
        <p>¡Bienvenido de nuevo, <?php echo $_SESSION['username']; ?>!</p>
    </div>
    <div class="container">
        <div class="row">
            <!-- Formulario para el registro de una nueva estudiante -->
            <div class="col-md-4 form">
                <div class="card">
                    <div class="card-header text-center">
                        <h6 class="mt-2 mb-2">Recolección de datos</h6>
                    </div>
                    <div class="card-body">
                        <form action="./notasest.php" method="POST">
                            <input type="hidden" name="action" value="create">
                            <div class="form-group mb-3">
                                <label for="estudiante">
                                    Nombre del estudiante:
                                    <small><strong class="text-danger">*</strong></small>
                                </label>
                                <input class="form-control form-control-sm" type="text" name="estudiante" id="estudiante" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="asignatura">
                                    Asignatura o Materia:
                                    <small><strong class="text-danger">*</strong></small>
                                </label>
                                <input class="form-control form-control-sm" type="text" name="asignatura" id="asignatura" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="PP">
                                    Primer Parcial:
                                    <small><strong class="text-danger">*</strong></small>
                                </label>
                                <input class="form-control form-control-sm" type="number" name="PP" id="PP" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="SP">
                                    Segundo Parcial:
                                    <small><strong class="text-danger">*</strong></small>
                                </label>
                                <input class="form-control form-control-sm" type="number" name="SP" id="SP" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="TI">
                                    Trabajo de Investigación:
                                    <small><strong class="text-danger">*</strong></small>
                                </label>
                                <input class="form-control form-control-sm" type="number" name="TI" id="TI" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="TP">
                                    Trabajo Practico:
                                    <small><strong class="text-danger">*</strong></small>
                                </label>
                                <input class="form-control form-control-sm" type="number" name="TP" id="TP" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="EF">
                                    Examen Final:
                                    <small><strong class="text-danger">*</strong></small>
                                </label>
                                <input class="form-control form-control-sm" type="number" name="EF" id="EF" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mt-3 btn-success">Añadir</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <p class="mb-0">
                            Los campos marcados por un <strong class="text-danger">(*)</strong> son requeridos.
                        </p>
                    </div>
                </div>
            </div>
            <!-- Tabla de estudiantes -->
            <div class="col-md-8 form">
                <div class="card">
                    <div class="card-header text-white text-center <?php echo $class_bg; ?>">
                        <h6 class="mt-2 mb-2">Lista de Estudiantes</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <th>Estudiante</th>
                                <th>Asignatura</th>
                                <th>PP(10)</th>
                                <th>SP(15)</th>
                                <th>TI(25)</th>
                                <th>TP(30)</th>
                                <th>EF(20)</th>
                                <th>CF</th>
                                <th>Letra</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>
                                <?php if (isset($_SESSION['estudiantes']) && !empty($_SESSION['estudiantes'])) : ?>
                                    <?php
                                    $total = 0;
                                    $cont = 0;
                                    $indice = 0;
                                    while ($cont != count($_SESSION['estudiantes'])) {
                                        if (isset($_SESSION['estudiantes'][$indice])) {
                                    ?>
                                            <tr>
                                                <td><?php echo $_SESSION['estudiantes'][$indice]; ?></td>
                                                <td><?php echo $_SESSION['asignaturas'][$indice]; ?></td>
                                                <td><?php echo $_SESSION['PPs'][$indice]; ?></td>
                                                <td><?php echo $_SESSION['SPs'][$indice]; ?></td>
                                                <td><?php echo $_SESSION['TIs'][$indice]; ?></td>
                                                <td><?php echo $_SESSION['TPs'][$indice]; ?></td>
                                                <td><?php echo $_SESSION['EFs'][$indice]; ?></td>
                                                <td><?php echo number_format(((float)$_SESSION['PPs'][$indice]) + ((float)$_SESSION['SPs'][$indice]) + ((float)$_SESSION['TIs'][$indice]) + ((float)$_SESSION['TPs'][$indice]) + ((float)$_SESSION['EFs'][$indice])); ?></td>
                                                <td><?php $nota = (((float)$_SESSION['PPs'][$indice]) + ((float)$_SESSION['SPs'][$indice]) + ((float)$_SESSION['TIs'][$indice]) + ((float)$_SESSION['TPs'][$indice]) + ((float)$_SESSION['EFs'][$indice]));
                                                    if ($nota >= 90 && $nota <= 100) {
                                                        echo "A";
                                                    } elseif ($nota >= 80 && $nota <= 89) {
                                                        echo "B";
                                                    } elseif ($nota >= 70 && $nota <= 79) {
                                                        echo "C";
                                                    } elseif ($nota >= 60 && $nota <= 69) {
                                                        echo "D";
                                                    } elseif ($nota <= 59 && $nota >= 0) {
                                                        echo "F";
                                                    } else {
                                                        echo "X";
                                                    }
                                                    ?></td>
                                                <td>
                                                    <form action="./notasest.php" method="POST">
                                                        <input type="hidden" name="action" value="completed">
                                                        <input type="hidden" name="id" value="<?php echo $indice; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm">Borrar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php $cont++;
                                        }
                                        $indice++;
                                    } ?>
                                <?php else : ?>
                                    <tr class="text-center">
                                        <td colspan="10">No hay ninguna nómina registrada</td>
                                    </tr>
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <p class="mb-0">
                            <?php if (isset($_SESSION['estudiantes'])) : ?>
                                <?php if (count($_SESSION['estudiantes']) == 0) : ?>
                                    <strong class="text-success">
                                        ¡No tienes ningún estudiante en la lista!
                                    </strong>
                                <?php endif ?>
                            <?php endif ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>