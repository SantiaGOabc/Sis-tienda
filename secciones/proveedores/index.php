<?php
session_start();
include("../../bd.php");

// Verificar si hay una sesión activa y obtener el nombre de usuario
$usuario_actual = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

if (isset($_GET['id'])) {
    $id_proveedor = (isset($_GET['id'])) ? $_GET['id'] : "";

    // Actualizar el campo "activo" a 0 para desactivar el proveedor
    $sentencia = $conexion->prepare("UPDATE proveedor SET activo = 0 WHERE id = :id");
    $sentencia->bindParam(":id", $id_proveedor);
    $sentencia->execute();

    header("Location:index.php");
    exit(); 
}

// Consulta para obtener la lista de proveedores activos
$consulta_proveedores = $conexion->query("SELECT * FROM proveedor WHERE activo = 1");
$lista_proveedores = $consulta_proveedores->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>
<!-- Lista de Proveedores -->
<div class="card">
    <div class="card-header">
        <a class="btn btn-primary" href="crear.php" role="button">Nuevo Proveedor</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Nombre de Referencia</th>
                        <th scope="col">NIT</th>
                        <th scope="col">Razón Social</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_proveedores as $proveedor): ?>
                        <tr>
                            <td><?php echo $proveedor['nombre']; ?></td>
                            <td><?php echo $proveedor['telefono']; ?></td>
                            <td><?php echo $proveedor['direccion']; ?></td>
                            <td><?php echo $proveedor['nombre_referencia']; ?></td>
                            <td><?php echo $proveedor['NIT']; ?></td>
                            <td><?php echo $proveedor['razon_social']; ?></td>
                            <td>
                                <a href="editar.php?id=<?php echo $proveedor['id']; ?>" class="btn btn-warning">Editar</a>
                                <a href="index.php?id=<?php echo $proveedor['id']; ?>" class="btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include("../../templates/footer.php"); ?>
