<?php
include("../../../bd.php");

if ($_POST) {
    // Obtener el ID del distribuidor y los IDs de los pedidos
    $iddistribuidor = $_POST["distribuidor_iddistribuidor"];
    $ruta_distribuidor = $_POST["ruta_distribuidor"]; // Aquí se asigna la ruta automáticamente
    $fecha_asignacion = $_POST["fecha_asignacion"];
    $fecha_entrega = $_POST["fecha_entrega"];

    // Insertar la relación en la tabla distribuidor_has_pedido
    $sentencia_relacion = $conexion->prepare("INSERT INTO distribuidor_has_pedido (distribuidor_iddistribuidor, pedido_idPedido) VALUES (:distribuidor_iddistribuidor, :pedido_idPedido)");
    $sentencia_relacion->bindParam(":distribuidor_iddistribuidor", $iddistribuidor);

    // Obtener los pedidos automáticamente y asignarlos al distribuidor
    foreach ($lista_distribuidores as $distribuidor) {
        $iddistribuidor = $distribuidor['iddistribuidor'];

        // Obtener los pedidos disponibles más antiguos
        $sentencia_pedidos = $conexion->prepare("SELECT idPedido FROM pedido WHERE distribuidor_id IS NULL ORDER BY fecha_pedido ASC LIMIT 5");
        $sentencia_pedidos->execute();
        $pedidos_disponibles = $sentencia_pedidos->fetchAll(PDO::FETCH_ASSOC);

        // Asignar los pedidos al distribuidor
        foreach ($pedidos_disponibles as $pedido) {
            $idPedido = $pedido['idPedido'];
            
            // Insertar la relación en la tabla distribuidor_has_pedido
            $sentencia_relacion->bindParam(":pedido_idPedido", $idPedido);
            $sentencia_relacion->execute();
            
            // Actualizar el pedido para marcar que ha sido asignado
            $sentencia_actualizar_pedido = $conexion->prepare("UPDATE pedido SET distribuidor_id = :distribuidor_id WHERE idPedido = :idPedido");
            $sentencia_actualizar_pedido->bindParam(":distribuidor_id", $iddistribuidor);
            $sentencia_actualizar_pedido->bindParam(":idPedido", $idPedido);
            $sentencia_actualizar_pedido->execute();
        }
    }

    // Redireccionar a alguna página
    header("Location: alguna_pagina.php");
    exit();
}

// Obtener lista de distribuidores para mostrar en el formulario
$sentencia_distribuidores = $conexion->prepare("SELECT iddistribuidor, fecha_asignacion, fecha_entrega, nombre FROM distribuidor d INNER JOIN empleados e ON d.datos = e.id_empleado WHERE e.activo = 1 && d.activo = 1");
$sentencia_distribuidores->execute();
$lista_distribuidores = $sentencia_distribuidores->fetchAll(PDO::FETCH_ASSOC);

include("../../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
        Asignar Pedidos a Distribuidor
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="mb-3">
                <label for="distribuidor_iddistribuidor" class="form-label">Seleccionar Distribuidor:</label>
                <select class="form-select" name="distribuidor_iddistribuidor" id="distribuidor_iddistribuidor">
                    <option value="">Seleccionar un distribuidor</option>
                    <?php foreach ($lista_distribuidores as $distribuidor): ?>
                        <option value="<?php echo $distribuidor['iddistribuidor']; ?>"><?php echo $distribuidor['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="ruta_distribuidor" value="<?php echo $ruta_distribuidor_automatica; ?>">
            <input type="hidden" name="fecha_asignacion" value="<?php echo $fecha_asignacion; ?>">
            <input type="hidden" name="fecha_entrega" value="<?php echo $fecha_entrega; ?>">
            <button type="submit" class="btn btn-primary">Asignar Pedidos</button>
        </form>
    </div>
</div>

<?php include("../../../templates/footer.php"); ?>
