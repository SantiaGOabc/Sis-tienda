<?php
include("../../bd.php");

// Obtener los pedidos con los detalles de clientes y productos
$sentencia_pedidos = $conexion->prepare("
    SELECT p.idPedido, p.monto_total, p.fecha_pedido,
        GROUP_CONCAT(DISTINCT CONCAT(c.nombre, ' ', c.apellido) SEPARATOR ', ') AS N_C,
        GROUP_CONCAT(CONCAT(pr.nombre, ' (', ph.cantidad, ')') SEPARATOR ', ') AS productos
    FROM pedido p
    INNER JOIN cliente c ON p.cliente_idcliente = c.idcliente
    INNER JOIN productos_has_pedido ph ON p.idPedido = ph.Pedido_idPedido
    INNER JOIN productos pr ON ph.productos_id_producto = pr.id_producto
    GROUP BY p.idPedido, p.monto_total, p.fecha_pedido
");
$sentencia_pedidos->execute();
$pedidos = $sentencia_pedidos->fetchAll(PDO::FETCH_ASSOC);

// Obtener los ID de los pedidos que ya han sido vendidos
$sentencia_pedidos_vendidos = $conexion->query("SELECT DISTINCT pedido FROM ventas");
$pedidos_vendidos = $sentencia_pedidos_vendidos->fetchAll(PDO::FETCH_COLUMN);

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
        Lista de Pedidos
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Estado</th>
                    <th>Cliente</th>
                    <th>Fecha Pedido</th>
                    <th>Productos (Cantidad)</th>
                    <th>Monto Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pedidos as $pedido): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pedido['idPedido']); ?></td>
                        <td><?php echo htmlspecialchars(in_array($pedido['idPedido'], $pedidos_vendidos) ? 'Entregado' : ''); ?></td>
                        <td><?php echo htmlspecialchars($pedido['N_C']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['productos']); ?></td>
                        <td><?php echo htmlspecialchars($pedido['monto_total']); ?></td>
                        <td>
                            <a href="editar.php?id=<?php echo htmlspecialchars($pedido['idPedido']); ?>" class="btn btn-warning">Editar</a>
                            <a href="cancelar_pedido.php?id=<?php echo htmlspecialchars($pedido['idPedido']); ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas cancelar este pedido?');">Cancelar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
