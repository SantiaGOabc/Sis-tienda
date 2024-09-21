<?php
session_start();

// Verificar si el usuario tiene el rol de distribuidor (suponiendo que el rol de distribuidor es 2)
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != 2) {
    echo "Acceso denegado.";
    exit();
}

include("../../bd.php");

// Obtener el ID del distribuidor logueado
$id_distribuidor = $_SESSION["iddistribuidor"];

// Consulta para obtener las ventas del distribuidor logueado
$sentencia_ventas = $conexion->prepare("
    SELECT v.idventas, v.fecha_entrega, v.estado_entrega, v.forma_pago_idforma_pago, v.pedido, p.monto_total, p.fecha_pedido,
           GROUP_CONCAT(DISTINCT CONCAT(c.nombre, ' ', c.apellido) SEPARATOR ', ') AS N_C,
           GROUP_CONCAT(CONCAT(pr.nombre, ' (', ph.cantidad, ')') SEPARATOR ', ') AS productos
    FROM ventas v
    INNER JOIN pedido p ON v.pedido = p.idPedido
    INNER JOIN cliente c ON p.cliente_idcliente = c.idcliente
    INNER JOIN productos_has_pedido ph ON p.idPedido = ph.Pedido_idPedido
    INNER JOIN productos pr ON ph.productos_id_producto = pr.id_producto
    WHERE v.distribuidor_iddistribuidor = :id_distribuidor
    GROUP BY v.idventas, v.fecha_entrega, v.estado_entrega, v.forma_pago_idforma_pago, v.pedido, p.monto_total, p.fecha_pedido
");
$sentencia_ventas->bindParam(":id_distribuidor", $id_distribuidor);
$sentencia_ventas->execute();
$ventas = $sentencia_ventas->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
        Lista de Ventas
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Fecha de Entrega</th>
                    <th>Estado de Entrega</th>
                    <th>Forma de Pago</th>
                    <th>ID Pedido</th>
                    <th>Monto Total</th>
                    <th>Fecha Pedido</th>
                    <th>Cliente</th>
                    <th>Productos (Cantidad)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ventas as $venta): ?>
                    <tr>
                        <td><?php echo $venta['idventas']; ?></td>
                        <td><?php echo $venta['fecha_entrega']; ?></td>
                        <td><?php echo ($venta['estado_entrega'] == 1) ? 'Entregado' : 'No entregado'; ?></td>
                        <td><?php echo $venta['forma_pago_idforma_pago']; ?></td>
                        <td><?php echo $venta['pedido']; ?></td>
                        <td><?php echo $venta['monto_total']; ?></td>
                        <td><?php echo $venta['fecha_pedido']; ?></td>
                        <td><?php echo $venta['N_C']; ?></td>
                        <td><?php echo $venta['productos']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
