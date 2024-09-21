<?php
session_start();
include("../../bd.php");

// Verificar si se ha proporcionado el ID del pedido a editar
if (isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];

    // Obtener los datos actuales del pedido
    $sentencia = $conexion->prepare("SELECT * FROM pedido WHERE idPedido = :id_pedido");
    $sentencia->bindParam(":id_pedido", $id_pedido);
    $sentencia->execute();
    $pedido = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Obtener los productos asociados al pedido
    $sentencia_productos_pedido = $conexion->prepare("SELECT ph.*, p.nombre FROM productos_has_pedido ph INNER JOIN productos p ON ph.productos_id_producto = p.id_producto WHERE ph.Pedido_idPedido = :id_pedido");
    $sentencia_productos_pedido->bindParam(":id_pedido", $id_pedido);
    $sentencia_productos_pedido->execute();
    $productos_pedido = $sentencia_productos_pedido->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se envió el formulario de actualización
    if ($_POST) {
        // Procesar los datos actualizados de los productos del pedido
        foreach ($_POST['productos_seleccionados'] as $id_producto => $cantidad) {
            $sentencia_actualizar_producto = $conexion->prepare("UPDATE productos_has_pedido SET cantidad = :cantidad WHERE productos_id_producto = :id_producto AND Pedido_idPedido = :id_pedido");
            $sentencia_actualizar_producto->bindParam(":cantidad", $cantidad);
            $sentencia_actualizar_producto->bindParam(":id_producto", $id_producto);
            $sentencia_actualizar_producto->bindParam(":id_pedido", $id_pedido);
            $sentencia_actualizar_producto->execute();
        }

        // Redirigir de vuelta al listado de pedidos
        header("Location: listado_p.php");
        exit;
    }
} else {
    // Si no se proporciona un ID de pedido, redirigir de vuelta al listado de pedidos
    header("Location: listado_p.php");
    exit;
}

include("../../templates/header.php");
?>

<!-- Formulario de Edición de Productos del Pedido -->
<br>
<div class="card">
    <div class="card-header">Editar Productos del Pedido</div>
    <div class="card-body">
        <form action="" method="post">
            <div class="mb-3">
                <h5>Pedido ID: <?php echo isset($id_pedido) ? $id_pedido : ''; ?></h5>
            </div>
            <div class="mb-3">
                <?php if (!empty($productos_pedido)): ?>
                    <?php foreach ($productos_pedido as $producto_pedido): ?>
                        <div class="mb-3">
                            <label for="cantidad_<?php echo $producto_pedido['productos_id_producto']; ?>" class="form-label"><?php echo $producto_pedido['nombre']; ?></label>
                            <input type="number" class="form-control" name="productos_seleccionados[<?php echo $producto_pedido['productos_id_producto']; ?>]" id="cantidad_<?php echo $producto_pedido['productos_id_producto']; ?>" value="<?php echo $producto_pedido['cantidad']; ?>" placeholder="Cantidad" required>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay productos asociados a este pedido.</p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a class="btn btn-secondary" href="listado_p.php" role="button">Cancelar</a>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
