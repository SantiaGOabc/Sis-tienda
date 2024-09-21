<?php
session_start();
include("../../bd.php");

// Verificar si se ha proporcionado el ID del cliente y actualizar su estado a inactivo
if (isset($_GET['id'])) {
    $id_cliente = $_GET['id'];

    // Actualizar el campo "activo" a 0 para desactivar el cliente
    $sentencia = $conexion->prepare("UPDATE cliente SET activo = 0 WHERE idcliente = :idcliente");
    $sentencia->bindParam(":idcliente", $id_cliente);
    $sentencia->execute();

    header("Location: index.php");
    exit();
}

// Consulta SQL para obtener la lista de clientes activos
$sentencia = $conexion->prepare("
    SELECT c.*, e.nombre AS nombre_prevendedor 
    FROM cliente c 
    INNER JOIN prevendedor p ON c.prevendedor_idprevendedor = p.idprevendedor
    INNER JOIN empleados e ON p.datos = e.id_empleado
    WHERE c.activo = 1
");
$sentencia->execute();
$lista_clientes = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>

<!-- Lista de Clientes -->
<div class="card">
    <div class="card-body">
        <div class="card-header">
            <a class="btn btn-primary" href="crear.php" role="button">CREAR</a>
        </div>
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellido</th>
                        <th scope="col">Razón Social</th>
                        <th scope="col">NIT/CI</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Referencia</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Código</th>
                        <th scope="col">Imagen de Tienda</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_clientes as $cliente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['razonSocial']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['NIT_CI']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['Direccion']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['Referencia']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['telf']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['codigo']); ?></td>
                            <td>
                                <?php if (!empty($cliente['imagen_tienda'])): ?>
                                    <img src="<?php echo htmlspecialchars($cliente['imagen_tienda']); ?>" alt="Imagen de Tienda" width="100" height="100">
                                <?php else: ?>
                                    No hay imagen
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="editar.php?id=<?php echo htmlspecialchars($cliente['idcliente']); ?>" class="btn btn-warning">Editar</a>
                                <a href="index.php?id=<?php echo htmlspecialchars($cliente['idcliente']); ?>" class="btn btn-danger">Desactivar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
