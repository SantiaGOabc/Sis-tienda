<?php
session_start();

// Verificar si el usuario tiene el rol de distribuidor (suponiendo que el rol de distribuidor es 2)
if (!isset($_SESSION["rol"]) || $_SESSION["rol"] != 2) {
    echo "Acceso denegado.";
    exit();
}

// Obtener la ruta del distribuidor logueado
$ruta_distribuidor = $_SESSION["dis_ruta"];

include("../../bd.php");

// Obtener los pedidos disponibles filtrados por la ruta del distribuidor y que no han sido vendidos
$sentencia_pedidos = $conexion->prepare("
    SELECT p.idPedido, p.monto_total, p.fecha_pedido,
    GROUP_CONCAT(DISTINCT CONCAT(c.nombre, ' ', c.apellido) SEPARATOR ', ') AS N_C,
           GROUP_CONCAT(CONCAT(pr.nombre, ' (', ph.cantidad, ')') SEPARATOR ', ') AS productos
    FROM pedido p
    INNER JOIN cliente c ON p.cliente_idcliente = c.idcliente
    INNER JOIN productos_has_pedido ph ON p.idPedido = ph.Pedido_idPedido
    INNER JOIN productos pr ON ph.productos_id_producto = pr.id_producto
    INNER JOIN prevendedor pv ON c.prevendedor_idprevendedor = pv.idprevendedor
    LEFT JOIN ventas v ON p.idPedido = v.pedido
    WHERE pv.ruta = :ruta_distribuidor AND v.pedido IS NULL
    GROUP BY p.idPedido, p.monto_total, p.fecha_pedido, c.nombre
");
$sentencia_pedidos->bindParam(":ruta_distribuidor", $ruta_distribuidor);
$sentencia_pedidos->execute();
$pedidos = $sentencia_pedidos->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de formas de pago
$consulta_forma_pago = $conexion->query("SELECT idforma_pago, pago FROM forma_pago");
$lista_forma_pago = $consulta_forma_pago->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_entrega = $_POST['fecha_entrega'] ?? null;
    $estado_entrega = $_POST['estado_entrega'] ?? null;
    $forma_pago_id = $_POST['forma_pago_id'] ?? null;
    $pedido_id = $_POST['pedido_id'] ?? null;

    // Insertar los datos en la tabla de ventas
    $sentencia_venta = $conexion->prepare("INSERT INTO ventas (fecha_entrega, estado_entrega, distribuidor_iddistribuidor, forma_pago_idforma_pago, pedido) 
                                     VALUES (:fecha_entrega, :estado_entrega, :iddistribuidor, :forma_pago_id, :pedido_id)");
    $sentencia_venta->bindParam(":fecha_entrega", $fecha_entrega);
    $sentencia_venta->bindParam(":estado_entrega", $estado_entrega);
    $sentencia_venta->bindParam(":iddistribuidor", $_SESSION['iddistribuidor']);
    $sentencia_venta->bindParam(":forma_pago_id", $forma_pago_id);
    $sentencia_venta->bindParam(":pedido_id", $pedido_id);
    $sentencia_venta->execute();

    // Redireccionar a la página de lista de ventas después de insertar
    header("Location: index.php");
    exit();
}

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
        Nueva Venta
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="estado_entrega" class="form-label">Estado de Entrega</label>
                <select class="form-select" id="estado_entrega" name="estado_entrega" required>
                    <option value="1">Entregado</option>
                    <option value="0">No entregado</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="forma_pago_id" class="form-label">Forma de Pago</label>
                <select class="form-select" id="forma_pago_id" name="forma_pago_id" required>
                    <option value="">Seleccionar forma de pago...</option>
                    <?php foreach ($lista_forma_pago as $forma_pago): ?>
                        <option value="<?php echo $forma_pago['idforma_pago']; ?>"><?php echo $forma_pago['pago']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="pedido_id" class="form-label">Pedido</label>
                <select class="form-select" id="pedido_id" name="pedido_id" required>
                    <option value="">Seleccionar pedido...</option>
                    <?php foreach ($pedidos as $pedido): ?>
                        <option value="<?php echo $pedido['idPedido']; ?>"><?php echo $pedido['idPedido']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
