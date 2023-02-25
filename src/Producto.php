<?php
require('./conexion.php');
if (!isset($_SESSION))
	session_start();

$con = Conectar();
$lastID = mysqli_fetch_object(mysqli_query($con, "SELECT ID_Producto from T_Producto order by ID_Producto desc limit 1")) ;

$_SESSION['URL'] = "./Producto.php";
/*
echo '<pre>';
	var_dump($_SESSION['cedulas']);
	return 0;
    */
if(!isset($_SESSION['edit']) || !isset($_SESSION['ID_Producto_edit']))
{
    $_SESSION['edit']=false;
    $_SESSION['ID_Producto_edit']=-1;
}

// verificando la autenticación de usuario
if (!isset($_SESSION['username']))
	header('Location: ./login.php');

if (!isset($_SESSION['ID_Productos'])){
    $_SESSION['ID_Productos'] = [];
	$_SESSION['Nombres'] = [];
	$_SESSION['Precio_Unitarios'] = [];

    $con = Conectar();
    $resultado = mysqli_query($con, "SELECT * FROM T_Producto");
    while ($producto = mysqli_fetch_assoc($resultado)) {
        // Mostrar los datos en la página web
        array_push($_SESSION['ID_Productos'], $producto["ID_Producto"]);
        array_push($_SESSION['Nombres'], $producto["Nombre"]);
        array_push($_SESSION['Precio_Unitarios'], $producto["Precio_Unitario"]);
    }
}

if (isset($_POST['action'])) {
	switch ($_POST['action']) {
		case 'create':
            if (!$_SESSION['edit']){
                array_push($_SESSION['ID_Productos'], (1+(int)$lastID->ID_Producto));
                array_push($_SESSION['Nombres'], $_POST['Nombre']);
                array_push($_SESSION['Precio_Unitarios'], $_POST['Precio_Unitario']);
    
                $con = Conectar();
                $result = mysqli_query($con, "insert into T_Producto values (". (1+(int)$lastID->ID_Producto) .", '".$_POST['Nombre']."', ".$_POST['Precio_Unitario'].")");
    
            }
            else{
                $ID_Producto = $_SESSION['ID_Producto_edit'];
                $_SESSION['ID_Productos'][$_SESSION['ID_pos']] = $ID_Producto;
                $_SESSION['Nombres'][$_SESSION['ID_pos']] = $_POST['Nombre'];
                $_SESSION['Precio_Unitarios'][$_SESSION['ID_pos']] = $_POST['Precio_Unitario'];
                $con = Conectar();
                $result = mysqli_query($con, "update T_Producto set Nombre='".$_POST['Nombre']."', Precio_Unitario='".$_POST['Precio_Unitario']."' where ID_Producto=". $ID_Producto);
                $_SESSION['edit']= false;
                $_SESSION['ID_Producto_edit'] = -1;
                $_SESSION['ID'] = -1;
            }
		
			header('Location: ./login.php');
			break;

		case 'completed':
			$id = $_POST['id'];
			unset($_SESSION['ID_Productos'][$id]);
			unset($_SESSION['Nombres'][$id]);
			unset($_SESSION['Precio_Unitarios'][$id]);
            $_SESSION['edit'] = false;
            $ID_Producto = $_POST['IDP'];
            $con = Conectar();
            $result = mysqli_query($con, "delete from T_Producto where ID_Producto=". $ID_Producto);

			header('Location: ./login.php');
			break;

        case 'edit':
            $_SESSION['ID_Producto_edit'] = $_POST['IDP'];
            $_SESSION['ID_pos'] = $_POST['ID'];
            $_SESSION['edit']= true;

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


$class_bg = 'bg-danger';
$cant_elementos = count($_SESSION['ID_Productos']);

if ($cant_elementos == 1)
	$class_bg = 'bg-primary';

if ($cant_elementos > 1)
	$class_bg = 'bg-success';

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>My TodoList</title>
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
			<li class="nav-item ">
				<a class="nav-link active" aria-current="page" href="./Producto.php">Productos</a>
			</li>
			
            <li class="nav-item ">
				<a class="nav-link text-white" aria-current="page" href="./nomina.php">Nomina</a>
			</li>
            <li class="nav-item ">
				<a class="nav-link text-white" aria-current="page" href="./notasest.php">Notas de Estudiantes</a>
			</li>
			<li class="nav-item ">
				<form action="./nomina.php" method="POST">
					<input type="hidden" name="action" value="logout">
					<button type="submit" class="nav-link text-white">Cerrar Sesión</button>
				</form>
			</li>
		</ul>
	</div>
	<div class="header container-fluid bg-dark text-white text-center mb-5">
		<h2>Control de Inventario</h2>
		<p>¡Bienvenido de nuevo, <?php echo $_SESSION['username']; ?>!</p>
	</div>
	<div class="container">
		<div class="row">
			<!-- Formulario para el registro de una nueva cedula -->
			<div class="col-md-4 form">
				<div class="card">
					<div class="card-header text-center">
						<h6 class="mt-2 mb-2">Recolección de datos</h6>
					</div>
					<div class="card-body">
						<form action="./Producto.php" method="POST">
							<input type="hidden" name="action" value="create">
							<div class="form-group mb-3">
								<label for="ID_Producto">
									Codigo Del Producto:
									<small><strong class="text-danger">*</strong></small>
								</label>
								<input class="form-control form-control-sm" type="text" name="ID_Producto" value="<?php if($_SESSION['edit'] && $_SESSION['ID_pos']!=-1){ $id=(int)$_SESSION['ID_pos']; echo $_SESSION['ID_Productos'][$id]; }else{ echo 1+(int)$lastID->ID_Producto;}?>"  disabled id="ID_Producto" required>
							</div>
							<div class="form-group mb-3">
								<label for="Nombre">
									Nombre Producto:
									<small><strong class="text-danger">*</strong></small>
								</label>
								<input class="form-control form-control-sm" type="text" name="Nombre" value="<?php if($_SESSION['edit'] && $_SESSION['ID_pos']!=-1) { echo $_SESSION['Nombres'][$id];}?>" id="Nombre" required>
							</div>
							<div class="form-group mb-3">
								<label for="Precio_Unitario">
									Precio del Producto:
									<small><strong class="text-danger">*</strong></small>
								</label>
								<input class="form-control form-control-sm" type="number" step="0.01" name="Precio_Unitario" value="<?php if($_SESSION['edit'] && $_SESSION['ID_pos']!=-1) {echo $_SESSION['Precio_Unitarios'][$id];}?>"  id="Precio_Unitario" required>
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
			<!-- Tabla de cedulas -->
			<div class="col-md-8 form">
				<div class="card">
					<div class="card-header text-white text-center <?php echo $class_bg; ?>">
						<h6 class="mt-2 mb-2">Inventario de Productos</h6>
					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead>
								<th>ID</th>
								<th>Nombre</th>
								<th>Precio</th>
								<th>Acciones</th>
							</thead>
							<tbody>
								<?php if (isset($_SESSION['ID_Productos']) && !empty($_SESSION['ID_Productos'])) : ?>
									<?php
									$total = 0;
									$cont = 0;
									$indice = 0;
									while ($cont != count($_SESSION['ID_Productos'])) {
										if (isset($_SESSION['ID_Productos'][$indice])) {
									?>
											<tr>
												<td><?php echo $_SESSION['ID_Productos'][$indice]; ?></td>
												<td><?php echo $_SESSION['Nombres'][$indice]; ?></td>
												<td><?php echo $_SESSION['Precio_Unitarios'][$indice]; ?></td>
												<td>
                                                <div class="btn-group">
                                                    <form action="./Producto.php" method="POST">
														<input type="hidden" name="action" value="edit">
                                                        <input type="hidden" name="ID" value="<?php echo $indice; ?>">
														<input type="hidden" name="IDP" value="<?php echo $_SESSION['ID_Productos'][$indice]; ?>">
														<button type="submit" class="btn btn-warning btn-sm">Editar</button>
													</form>
                                                    &nbsp;
													<form action="./Producto.php" method="POST">
														<input type="hidden" name="action" value="completed">
                                                        <input type="hidden" name="id" value="<?php echo $indice; ?>">
														<input type="hidden" name="IDP" value="<?php echo $_SESSION['ID_Productos'][$indice]; ?>">
														<button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
													</form>
                                                    
                                                    
                                                    </div>
												</td>
											</tr>
									<?php $cont++;
										}
										$indice++;
									} ?>
								<?php else : ?>
									<tr class="text-center">
										<td colspan="4">No hay ningun producto registrado</td>
									</tr>
								<?php endif ?>
							</tbody>
						</table>
					</div>
					<div class="card-footer">
						<p class="mb-0">
							<?php if (isset($_SESSION['ID_Productos'])) : ?>
								<?php if (count($_SESSION['ID_Productos']) == 0) : ?>
									<strong class="text-danger">
										¡No tienes ningún Producto Registrado!
									</strong>
								<?php endif ?>
								<?php if (count($_SESSION['ID_Productos']) >= 1) : ?>
									<strong style="font-size: 16px">
										El total de Producto es: <?php echo number_format($cont);  ?>
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