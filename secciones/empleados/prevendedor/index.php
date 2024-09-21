<?php
session_start();
include("../../../bd.php");

// Verificar si hay una sesión activa y obtener el nombre de usuario
$usuario_actual = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

// Verificar si se ha proporcionado el ID del prevendedor para eliminar o cambiar el estado a inactivo
if (isset($_GET['txtID'])) {
    $txtID = $_GET['txtID'];

    // Actualizar el campo "activo" a "No" o "0" en lugar de eliminar el registro
    $sentencia = $conexion->prepare("UPDATE prevendedor SET activo = '0' WHERE idprevendedor = :idprevendedor");
    $sentencia->bindParam(":idprevendedor", $txtID);
    $sentencia->execute();

    header("Location:index.php");
    exit(); 
}

// Corrección de la consulta SQL para obtener los datos de prevendedor
$sentencia = $conexion->prepare("
    SELECT p.*, e.nombre AS nombre_empleado, r.municipio AS nombre_ruta 
    FROM prevendedor p 
    INNER JOIN empleados e ON p.datos = e.id_empleado 
    INNER JOIN rutas r ON p.ruta = r.idruta
    where p.activo = 1;
");
$sentencia->execute();
$lista_prevendedores = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../../templates/header.php");
?>
<!-- Lista de Prevendedores -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nombre Empleado</th>
                        <th scope="col">Fecha Asignación</th>
                        <th scope="col">Fecha Visita</th>
                        <th scope="col">Ruta</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_prevendedores as $prevendedor): ?>
                        <tr>
                            <td><?php echo ($prevendedor['nombre_empleado']); ?></td>
                            <td><?php echo ($prevendedor['fecha_asignacion']); ?></td>
                            <td><?php echo ($prevendedor['fecha_visita']); ?></td>
                            <td><?php echo ($prevendedor['nombre_ruta']); ?></td>
                            <td>
                                <a href="editar.php?txtID=<?php echo $prevendedor['idprevendedor']; ?>" class="btn btn-warning">Editar</a>
                                <a href="index.php?txtID=<?php echo $prevendedor['idprevendedor']; ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

