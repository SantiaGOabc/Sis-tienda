
<?php
session_start();
include("../../../bd.php");

// Verificar si hay una sesión activa y obtener el nombre de usuario actual
$usuario_actual = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

// Obtener listado de rutas
$sentencia = $conexion->prepare("SELECT * FROM rutas");
$sentencia->execute();
$lista_rutas = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../../templates/header.php");
?>

<!-- Listado de Rutas -->
<br>
<div class="card">
    <div class="card-header">
        <a class="btn btn-primary" href="crear.php" role="button">Crear Nueva Ruta</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Municipio</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_rutas as $ruta): ?>
                        <tr>
                            <td><?php echo $ruta['codigo']; ?></td>
                            <td><?php echo $ruta['municipio']; ?></td>
                            <td>
                                <?php if (!empty($ruta['imagen'])): ?>
                                    <img src="<?php echo $ruta['imagen']; ?>" alt="Imagen de la Ruta" width="100px">
                                <?php else: ?>
                                    No hay imagen
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="editar.php?idruta=<?php echo $ruta['idruta']; ?>" class="btn btn-warning">Editar</a>
                                <a href="eliminar.php?idruta=<?php echo $ruta['idruta']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta ruta?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

