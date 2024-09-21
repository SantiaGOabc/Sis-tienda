<?php
session_start();
include("../../bd.php");

// Obtener lista de distribuidores y forma de pago para el formulario
$consulta_distribuidores = $conexion->query("SELECT d.iddistribuidor, CONCAT(e.nombre, ' ', e.apellido) AS nombre_completo FROM distribuidor d INNER JOIN empleados e ON d.datos = e.id_empleado");
$lista_distribuidores = $consulta_distribuidores->fetchAll(PDO::FETCH_ASSOC);

$consulta_forma_pago = $conexion->query("SELECT idforma_pago, pago FROM forma_pago");
$lista_forma_pago = $consulta_forma_pago->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de pedidos disponibles (cola)
$consulta_pedidos_disponibles = $conexion->query("SELECT idPedido FROM pedido WHERE idPedido NOT IN (SELECT pedido FROM ventas)");
$lista_pedidos_disponibles = $consulta_pedidos_disponibles->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_entrega = $_POST['fecha_entrega'] ?? null;
    $estado_entrega = $_POST['estado_entrega'] ?? null;
    $distribuidor_id = $_POST['distribuidor_id'] ?? null;
    $forma_pago_id = $_POST['forma_pago_id'] ?? null;
    $pedido_id = $_POST['pedido_id'] ?? null;

    // Validar los datos (aquí deberías realizar validaciones según tus requerimientos)

    // Insertar los datos en la tabla de ventas
    $sentencia = $conexion->prepare("INSERT INTO ventas (fecha_entrega, estado_entrega, distribuidor_iddistribuidor, forma_pago_idforma_pago, pedido) 
                                     VALUES (:fecha_entrega, :estado_entrega, :distribuidor_id, :forma_pago_id, :pedido_id)");
    $sentencia->bindParam(":fecha_entrega", $fecha_entrega);
    $sentencia->bindParam(":estado_entrega", $estado_entrega);
    $sentencia->bindParam(":distribuidor_id", $distribuidor_id);
    $sentencia->bindParam(":forma_pago_id", $forma_pago_id);
    $sentencia->bindParam(":pedido_id", $pedido_id);
    $sentencia->execute();

    // Redireccionar a la página de lista de ventas después de insertar
    header("Location: index.php");
    exit();
}

include("../../templates/header.php");
?>

<!-- Formulario de Creación de Venta -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Nueva Venta</h5>
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
                <label for="distribuidor_id" class="form-label">Distribuidor</label>
                <select class="form-select" id="distribuidor_id" name="distribuidor_id" required>
                    <option value="">Seleccionar distribuidor...</option>
                    <?php foreach ($lista_distribuidores as $distribuidor): ?>
                        <option value="<?php echo $distribuidor['iddistribuidor']; ?>"><?php echo $distribuidor['nombre_completo']; ?></option>
                    <?php endforeach; ?>
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
                    <?php foreach ($lista_pedidos_disponibles as $pedido): ?>
                        <option value="<?php echo $pedido['idPedido']; ?>"><?php echo $pedido['idPedido']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
