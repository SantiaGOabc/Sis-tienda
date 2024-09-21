<?php
session_start();
include("../../../bd.php");

// Verificar si hay una sesión activa y obtener el nombre de usuario
$usuario_actual = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

// Verificar si se ha proporcionado el ID del distribuidor para eliminar o cambiar el estado a inactivo
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Actualizar el campo "activo" a 0 para desactivar el distribuidor
    $sentencia = $conexion->prepare("UPDATE distribuidor SET activo = 0 WHERE iddistribuidor = :id");
    $sentencia->bindParam(":id", $id);
    $sentencia->execute();

    // Redireccionar a la misma página para ver los cambios
    header("Location: index.php");
    exit();
}

// Consulta para obtener la lista de distribuidores activos
$consulta_distribuidores = $conexion->query("
    SELECT d.iddistribuidor,d.ruta, e.nombre, e.telefono, e.direccion, e.correo 
    FROM distribuidor d
    JOIN empleados e ON d.datos = e.id_empleado
    WHERE d.activo = 1
");
$lista_distribuidores = $consulta_distribuidores->fetchAll(PDO::FETCH_ASSOC);

include("../../../templates/header.php");
?>

<!-- Lista de Distribuidores -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Ruta</th>
                        <th scope="col">Email</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_distribuidores as $distribuidor): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($distribuidor['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($distribuidor['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($distribuidor['direccion']); ?></td>
                            <td><?php echo htmlspecialchars($distribuidor['ruta']); ?></td>
                            <td><?php echo htmlspecialchars($distribuidor['correo']); ?></td>
                            <td>
                                <a href="editar.php?id=<?php echo $distribuidor['iddistribuidor']; ?>" class="btn btn-warning">Editar</a>
                                <a href="#" class="btn btn-danger" onclick="confirmDesactivar('<?php echo $distribuidor['iddistribuidor']; ?>')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDesactivar(id) {
    Swal.fire({
        title: '¿Está seguro de que desea desactivar este distribuidor?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "index.php?id=" + id;
        }
    })
}
</script>

<?php include("../../../templates/footer.php"); ?>
