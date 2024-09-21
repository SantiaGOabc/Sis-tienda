<?php
session_start();
include("../../bd.php");

// Obtener los productos para el formulario
$sentencia_productos = $conexion->prepare("SELECT * FROM productos WHERE Activo = 1");
$sentencia_productos->execute();
$productos = $sentencia_productos->fetchAll(PDO::FETCH_ASSOC);

// Obtener los clientes para el formulario
$sentencia_clientes = $conexion->prepare("SELECT * FROM cliente where activo =1");
$sentencia_clientes->execute();
$clientes = $sentencia_clientes->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    $cliente_id = isset($_POST["cliente"]) ? $_POST["cliente"] : "";
    $productos_seleccionados = isset($_POST["productos_seleccionados"]) ? $_POST["productos_seleccionados"] : array();
    $fecha_pedido = date("Y-m-d");
    $total_pedido = 0;

    foreach ($productos_seleccionados as $id_producto => $cantidad) {
        if ($cantidad > 0) {
            $sentencia_precio = $conexion->prepare("SELECT precio_venta, stock FROM productos WHERE id_producto = :id_producto");
            $sentencia_precio->bindParam(":id_producto", $id_producto);
            $sentencia_precio->execute();
            $producto = $sentencia_precio->fetch(PDO::FETCH_ASSOC);

            if ($producto && $cantidad <= $producto["stock"]) {
                $precio_producto = $producto["precio_venta"];
                $total_producto = $precio_producto * $cantidad;
                $total_pedido += $total_producto;

                $sentencia_stock = $conexion->prepare("UPDATE productos SET stock = stock - :cantidad WHERE id_producto = :id_producto");
                $sentencia_stock->bindParam(":cantidad", $cantidad);
                $sentencia_stock->bindParam(":id_producto", $id_producto);
                $sentencia_stock->execute();
            } else {
                echo "Error: La cantidad seleccionada de producto excede el stock disponible.";
                exit;
            }
        }
    }

    $sentencia_pedido = $conexion->prepare("INSERT INTO pedido (monto_total, fecha_pedido, cliente_idcliente) VALUES (:monto_total, :fecha_pedido, :cliente_id)");
    $sentencia_pedido->bindParam(":monto_total", $total_pedido);
    $sentencia_pedido->bindParam(":fecha_pedido", $fecha_pedido);
    $sentencia_pedido->bindParam(":cliente_id", $cliente_id);
    $sentencia_pedido->execute();

    $pedido_id = $conexion->lastInsertId();

    foreach ($productos_seleccionados as $id_producto => $cantidad) {
        if ($cantidad > 0) {
            $sentencia_relacion = $conexion->prepare("INSERT INTO productos_has_pedido (productos_id_producto, Pedido_idPedido, cantidad) VALUES (:producto_id, :pedido_id, :cantidad)");
            $sentencia_relacion->bindParam(":producto_id", $id_producto);
            $sentencia_relacion->bindParam(":pedido_id", $pedido_id);
            $sentencia_relacion->bindParam(":cantidad", $cantidad);
            $sentencia_relacion->execute();
        }
    }

    if (!isset($_SESSION['rol'])) {
        // Si no está autenticado, redireccionar al inicio de sesión
        header("Location: login.php");
        exit();
    }
    
    // Verificar el rol del usuario y redireccionar según sea necesario
    if ($_SESSION['rol'] == 2) {
        header("Location: listado_d.php");
        exit();
    } elseif ($_SESSION['rol'] == 3) {
        header("Location: listado_p.php");
        exit();
    } else {
        // Manejar otros roles si es necesario
        echo "Rol no reconocido.";
        exit();
    }
}

include("../../templates/header.php");
?>

<div class="card">
    <div class="card-header">
        Registrar Nuevo Pedido
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="mb-3">
                <label for="cliente" class="form-label">Cliente</label>
                <select class="form-select" name="cliente" id="cliente" required>
                    <option value="" selected>Seleccione un cliente</option>
                    <?php foreach($clientes as $cliente): ?>
                        <option value="<?php echo $cliente['idcliente']; ?>"><?php echo $cliente['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="productos" class="form-label">Productos</label>
                <input type="text" id="buscador" placeholder="Buscar productos">
                <div id="lista-productos">
                    <?php foreach($productos as $producto): ?>
                        <div>
                            <input type="number" min="0" name="productos_seleccionados[<?php echo $producto['id_producto']; ?>]" placeholder="Cantidad"> 
                            <?php echo $producto['nombre']; ?> - $<?php echo $producto['precio_venta']; ?> (Stock: <?php echo $producto['stock']; ?>)
                            <?php if (!empty($producto['imagen'])) : ?>
                                <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" alt="Imagen del Producto" width="50" height="50">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Pedido</button>
        </form>
    </div>
</div>

<script>
document.getElementById('buscador').addEventListener('input', function() {
    var input = this.value.toLowerCase();
    var productos = document.querySelectorAll('#lista-productos > div');

    productos.forEach(function(producto) {
        var nombre = producto.textContent.toLowerCase();
        if (nombre.includes(input)) {
            producto.style.display = 'block';
        } else {
            producto.style.display = 'none';
        }
    });
});
</script>

<?php include("../../templates/footer.php"); ?>
