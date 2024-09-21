<?php
session_start();
include("../../bd.php");

// Obtener lista de distribuidores y forma de pago para el formulario
$consulta_distribuidores = $conexion->query("SELECT d.iddistribuidor, CONCAT(e.nombre, ' ', e.apellido) AS nombre_completo FROM distribuidor d INNER JOIN empleados e ON d.datos = e.id_empleado");
$lista_distribuidores = $consulta_distribuidores->fetchAll(PDO::FETCH_ASSOC);

$consulta_forma_pago = $conexion->query("SELECT idforma_pago, pago FROM forma_pago");
$lista_forma_pago = $consulta_forma_pago->fetchAll(PDO::FETCH_ASSOC);

$consulta_pedido = $conexion->query("SELECT idPedido FROM pedido");
$lista_pedido = $consulta_pedido->fetchAll(PDO::FETCH_ASSOC);

// Verificar si se proporciona un ID de venta válido
if (!isset($_GET['id']) || !$_GET['id']) {
    header("Location: index.php");
    exit();
}

$id_venta = $_GET['id'];

// Obtener los datos de la venta a editar
$sentencia = $conexion->prepare("SELECT * FROM ventas WHERE idventas = :id");
$sentencia->bindParam(":id", $id_venta);
$sentencia->execute();
$venta = $sentencia->fetch(PDO::FETCH_ASSOC);

// Procesar el formulario cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_entrega = $_POST['fecha_entrega'] ?? null;
    $estado_entrega = $_POST['estado_entrega'] ?? null;
    $distribuidor_id = $_POST['distribuidor_id'] ?? null;
    $forma_pago_id = $_POST['forma_pago_id'] ?? null;
    $pedido_id = $_POST['pedido_id'] ?? null;


    // Actualizar los datos en la tabla de ventas
    $sentencia = $conexion->prepare("UPDATE ventas SET fecha_entrega = :fecha_entrega, estado_entrega = :estado_entrega, 
                                     distribuidor_iddistribuidor = :distribuidor_id, forma_pago_idforma_pago = :forma_pago_id, 
                                     pedido = :pedido_id WHERE idventas = :id");
    $sentencia->bindParam(":fecha_entrega", $fecha_entrega);
    $sentencia->bindParam(":estado_entrega", $estado_entrega);
    $sentencia->bindParam(":distribuidor_id", $distribuidor_id);
    $sentencia->bindParam(":forma_pago_id", $forma_pago_id);
    $sentencia->bindParam(":pedido_id", $pedido_id);
    $sentencia->bindParam(":id", $id_venta);
    $sentencia->execute();

    // Redireccionar a la página de lista de ventas después de actualizar
    header("Location: index.php");
    exit();
}

include("../../templates/header.php");
?>

<!-- Formulario de Edición de Venta -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Editar Venta</h5>
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                <input type="date" class="form-control" id="fecha_entrega" name="fecha_entrega" value="<?php echo $venta['fecha_entrega']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="estado_entrega" class="form-label">Estado de Entrega</label>
                <select class="form-select" id="estado_entrega" name="estado_entrega" required>
                    <option value="1" <?php echo ($venta['estado_entrega'] == 1) ? 'selected' : ''; ?>>Entregado</option>
                    <option value="0" <?php echo ($venta['estado_entrega'] == 0) ? 'selected' : ''; ?>>No entregado</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="distribuidor_id" class="form-label">Distribuidor</label>
                <select class="form-select" id="distribuidor_id" name="distribuidor_id" required>
                    <option value="">Seleccionar distribuidor...</option>
                    <?php foreach ($lista_distribuidores as $distribuidor): ?>
                        <option value="<?php echo $distribuidor['iddistribuidor']; ?>" <?php echo ($venta['distribuidor_iddistribuidor'] == $distribuidor['iddistribuidor']) ? 'selected' : ''; ?>><?php echo $distribuidor['nombre_completo']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="forma_pago_id" class="form-label">Forma de Pago</label>
                <select class="form-select" id="forma_pago_id" name="forma_pago_id" required>
                    <option value="">Seleccionar forma de pago...</option>
                    <?php foreach ($lista_forma_pago as $forma_pago): ?>
                        <option value="<?php echo $forma_pago['idforma_pago']; ?>" <?php echo ($venta['forma_pago_idforma_pago'] == $forma_pago['idforma_pago']) ? 'selected' : ''; ?>><?php echo $forma_pago['pago']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="pedido_id" class="form-label">Pedido</label>
                <select class="form-select" id="pedido_id" name="pedido_id" required>
                    <option value="">Seleccionar pedido...</option>
                    <?php foreach ($lista_pedido as $pedido): ?>
                        <option value="<?php echo $pedido['idPedido']; ?>" <?php echo ($venta['pedido'] == $pedido['idPedido']) ? 'selected' : ''; ?>><?php echo $pedido['idPedido']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
