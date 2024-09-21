<?php
session_start();
include("../../bd.php");

// Verificar si el usuario tiene el rol de distribuidor (rol 2)
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != 2) {
    echo "Acceso denegado.";
    exit();
}

// Obtener la ruta del distribuidor logueado
$ruta_distribuidor = $_SESSION["dis_ruta"];

// Manejo de errores
try {
    // Obtener los pedidos filtrados por la ruta del distribuidor logueado que no han sido vendidos
    $sentencia_pedidos = $conexion->prepare("
        SELECT p.idPedido, p.monto_total, p.fecha_pedido,
        GROUP_CONCAT(DISTINCT CONCAT(c.nombre, ' ', c.apellido) SEPARATOR ', ') AS N_C,
               GROUP_CONCAT(CONCAT(pr.nombre, ' (', ph.cantidad, ')') SEPARATOR ', ') AS productos
        FROM pedido p
        INNER JOIN cliente c ON p.cliente_idcliente = c.idcliente
        INNER JOIN productos_has_pedido ph ON p.idPedido = ph.Pedido_idPedido
        INNER JOIN productos pr ON ph.productos_id_producto = pr.id_producto
        INNER JOIN prevendedor pv ON c.prevendedor_idprevendedor = pv.idprevendedor
        WHERE pv.ruta = :ruta_distribuidor
        AND p.idPedido NOT IN (SELECT pedido FROM ventas)
        GROUP BY p.idPedido, p.monto_total, p.fecha_pedido
    ");
    $sentencia_pedidos->bindParam(":ruta_distribuidor", $ruta_distribuidor);
    $sentencia_pedidos->execute();
    $pedidos = $sentencia_pedidos->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

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
                    <th>Cliente</th>
                    <th>Fecha Pedido</th>
                    <th>Productos (Cantidad)</th>
                    <th>Monto Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pedidos)): ?>
                    <?php foreach($pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['idPedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['N_C']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['productos']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['monto_total']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay pedidos para mostrar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
