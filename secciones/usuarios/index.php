<?php
session_start();
include("../../bd.php");

// Verificar si hay una sesión activa y obtener el nombre de usuario
$usuario_actual = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

// Verificar si se ha proporcionado el ID del usuario para eliminar o cambiar el estado a inactivo
if (isset($_GET['txtID'])) {
    $txtID = $_GET['txtID'];

    // Actualizar el campo "activo" a 0 para desactivar el usuario
    $sentencia = $conexion->prepare("UPDATE usuario SET activo = 0 WHERE idusuario = :idusuario");
    $sentencia->bindParam(":idusuario", $txtID, PDO::PARAM_INT);
    $sentencia->execute();

    header("Location:index.php");
    exit(); 
}

// Corrección de la consulta SQL para obtener los datos de usuario
$sentencia = $conexion->prepare("
    SELECT u.*, r.rol AS nombre_rol, e.nombre AS n_usuario 
    FROM usuario u 
    INNER JOIN empleados e ON u.empleado = e.id_empleado 
    INNER JOIN rol r ON e.rol = r.idrol
    WHERE u.activo = 1
");
$sentencia->execute();
$lista_usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>
<!-- Lista de Usuarios -->
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
                        <th scope="col">Usuario</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Imagen de Perfil</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_usuarios as $registro): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($registro['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($registro['n_usuario']); ?></td>
                            <td><?php echo htmlspecialchars($registro['correo']); ?></td>
                            <td><?php echo htmlspecialchars($registro['nombre_rol']); ?></td>
                            <td>
                                <?php if ($registro['perfil']): ?>
                                    <img src="<?php echo htmlspecialchars($registro['perfil']); ?>" alt="Perfil" width="100px" height="auto">
                                <?php else: ?>
                                    No hay imagen
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="editar.php?txtID=<?php echo htmlspecialchars($registro['idusuario']); ?>" class="btn btn-warning">Editar</a>
                                <a href="index.php?txtID=<?php echo htmlspecialchars($registro['idusuario']); ?>" class="btn btn-danger">Eliminar</a>
                                <?php if ($usuario_actual == $registro['n_usuario']): ?>
                                    <span class="text-success">Sesión actual</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
