<?php
session_start();
include("../../bd.php");

if(isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    // Actualizar el campo "activo" a 0 para desactivar al empleado
    $sentencia = $conexion->prepare("UPDATE empleados SET activo = 0 WHERE id_empleado = :id_empleado");
    $sentencia->bindParam(":id_empleado", $txtID);
    $sentencia->execute();

    header("Location:index.php");
    exit(); 
}

$sentencia = $conexion->prepare("SELECT e.*, r.rol AS nombre_rol FROM `empleados` e INNER JOIN `rol` r ON e.rol = r.idrol WHERE e.activo = 1");
$sentencia->execute();
$lista_empleados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>
<!-- Lista de Empleados -->
<div class="card">
    <div class="card-header">
        <a class="btn btn-primary" href="crear.php" role="button">Nuevo</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">CI</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_empleados as $registro): ?>
                        <tr>
                            <td><?php echo ($registro['nombre']); ?></td>
                            <td><?php echo ($registro['apellido']); ?></td>
                            <td><?php echo ($registro['CI']); ?></td>
                            <td><?php echo ($registro['direccion']); ?></td>
                            <td><?php echo ($registro['telefono']); ?></td>
                            <td><?php echo ($registro['correo']); ?></td>
                            <td><?php echo ($registro['nombre_rol']); ?></td>
                            <td>
                                <a href="editar.php?txtID=<?php echo $registro['id_empleado']; ?>" class="btn btn-warning">Editar</a>
                                <a href="index.php?txtID=<?php echo $registro['id_empleado']; ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
