<?php
session_start();
include("../../bd.php");

// Consulta para obtener la lista de ventas
$sentencia = $conexion->prepare("
    SELECT v.*, CONCAT(e.nombre, ' ', e.apellido) AS nombre_distribuidor, fp.pago AS forma_pago, p.idPedido AS pedido_asociado
    FROM ventas v
    INNER JOIN distribuidor d ON v.distribuidor_iddistribuidor = d.iddistribuidor
    INNER JOIN forma_pago fp ON v.forma_pago_idforma_pago = fp.idforma_pago
    INNER JOIN pedido p ON v.pedido = p.idPedido
    INNER JOIN empleados e ON d.datos = e.id_empleado
");
$sentencia->execute();
$lista_ventas = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>

<!-- Lista de Ventas -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Lista de Ventas</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID Venta</th>
                        <th scope="col">Fecha de Entrega</th>
                        <th scope="col">Estado de Entrega</th>
                        <th scope="col">Distribuidor</th>
                        <th scope="col">Forma de Pago</th>
                        <th scope="col">Pedido Asociado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_ventas as $venta): ?>
                        <tr>
                            <td><?php echo $venta['idventas']; ?></td>
                            <td><?php echo $venta['fecha_entrega']; ?></td>
                            <td><?php echo $venta['estado_entrega']; ?></td>
                            <td><?php echo $venta['nombre_distribuidor']; ?></td>
                            <td><?php echo $venta['forma_pago']; ?></td>
                            <td><?php echo $venta['pedido_asociado']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
