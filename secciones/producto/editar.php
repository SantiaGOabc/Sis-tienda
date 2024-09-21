<?php
session_start();
include("../../bd.php");

// Verificar si se ha proporcionado el ID del producto a editar
if (isset($_GET['id_producto'])) {
    $id_producto = $_GET['id_producto'];

    // Obtener los datos actuales del producto
    $sentencia = $conexion->prepare("SELECT * FROM productos WHERE id_producto = :id_producto");
    $sentencia->bindParam(":id_producto", $id_producto);
    $sentencia->execute();
    $producto = $sentencia->fetch(PDO::FETCH_ASSOC);

    // Verificar si se envió el formulario de actualización
    if ($_POST) {
        $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
        $precio_compra = isset($_POST["precio_compra"]) ? $_POST["precio_compra"] : "";
        $precio_venta = isset($_POST["precio_venta"]) ? $_POST["precio_venta"] : "";
        $stock = isset($_POST["stock"]) ? $_POST["stock"] : "";
        $fecha_vencimiento = isset($_POST["fecha_vencimiento"]) ? $_POST["fecha_vencimiento"] : "";
        $fecha_compra_lote = isset($_POST["fecha_compra_lote"]) ? $_POST["fecha_compra_lote"] : "";
        $activo = isset($_POST["activo"]) ? $_POST["activo"] : "";
        $nombre_proveedor = isset($_POST["nombre_proveedor"]) ? $_POST["nombre_proveedor"] : "";

        // Verificar si se subió una nueva imagen
        if ($_FILES['imagen']['name']) {
            $imagen = $_FILES['imagen']['name'];
            $imagen_temp = $_FILES['imagen']['tmp_name'];
            $carpeta_imagenes = "../../imagenes/";
            $ruta_imagen = $carpeta_imagenes . basename($imagen);

            if (move_uploaded_file($imagen_temp, $ruta_imagen)) {
                // Eliminar la imagen anterior si existe
                if (!empty($producto['imagen'])) {
                    unlink($producto['imagen']);
                }
            } else {
                echo "Error al subir la imagen.";
            }
        } else {
            // Conservar la imagen existente si no se selecciona una nueva
            $ruta_imagen = $producto['imagen'];
        }

        // Actualizar los datos en la base de datos
        $sentencia_actualizar = $conexion->prepare("UPDATE productos SET nombre = :nombre, precio_compra = :precio_compra, precio_venta = :precio_venta, stock = :stock, fecha_vencimiento = :fecha_vencimiento, fecha_compra_lote = :fecha_compra_lote, Activo = :activo, nombre_proveedor = :nombre_proveedor, imagen = :imagen WHERE id_producto = :id_producto");
        $sentencia_actualizar->bindParam(":nombre", $nombre);
        $sentencia_actualizar->bindParam(":precio_compra", $precio_compra);
        $sentencia_actualizar->bindParam(":precio_venta", $precio_venta);
        $sentencia_actualizar->bindParam(":stock", $stock);
        $sentencia_actualizar->bindParam(":fecha_vencimiento", $fecha_vencimiento);
        $sentencia_actualizar->bindParam(":fecha_compra_lote", $fecha_compra_lote);
        $sentencia_actualizar->bindParam(":activo", $activo);
        $sentencia_actualizar->bindParam(":nombre_proveedor", $nombre_proveedor);
        $sentencia_actualizar->bindParam(":imagen", $ruta_imagen);
        $sentencia_actualizar->bindParam(":id_producto", $id_producto);

        if ($sentencia_actualizar->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error al actualizar el producto.";
        }
    }
}

include("../../templates/header.php");
?>

<!-- Formulario de Edición de Producto -->
<br>
<div class="card">
    <div class="card-header">Editar Producto</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $producto['nombre']; ?>" placeholder="Nombre del producto" required/>
            </div>
            <div class="mb-3">
                <label for="precio_compra" class="form-label">Precio Compra</label>
                <input type="number" step="0.01" class="form-control" name="precio_compra" id="precio_compra" value="<?php echo $producto['precio_compra']; ?>" placeholder="Precio de compra" required/>
            </div>
            <div class="mb-3">
                <label for="precio_venta" class="form-label">Precio Venta</label>
                <input type="number" step="0.01" class="form-control" name="precio_venta" id="precio_venta" value="<?php echo $producto['precio_venta']; ?>" placeholder="Precio de venta" required/>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" name="stock" id="stock" value="<?php echo $producto['stock']; ?>" placeholder="Stock disponible" required/>
            </div>
            <div class="mb-3">
                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                <input type="date" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento" value="<?php echo $producto['fecha_vencimiento']; ?>" required/>
            </div>
            <div class="mb-3">
                <label for="fecha_compra_lote" class="form-label">Fecha de Compra del Lote</label>
                <input type="date" class="form-control" name="fecha_compra_lote" id="fecha_compra_lote" value="<?php echo $producto['fecha_compra_lote']; ?>" required/>
            </div>
            <div class="mb-3">
                <label for="activo" class="form-label">Activo</label>
                <select class="form-control" name="activo" id="activo" required>
                    <option value="1" <?php echo $producto['Activo'] ? 'selected' : ''; ?>>Sí</option>
                    <option value="0" <?php echo !$producto['Activo'] ? 'selected' : ''; ?>>No</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nombre_proveedor" class="form-label">Nombre del Proveedor</label>
                <input type="text" class="form-control" name="nombre_proveedor" id="nombre_proveedor" value="<?php echo $producto['nombre_proveedor']; ?>" placeholder="Nombre del proveedor" required/>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen Actual</label><br>
                <?php if (!empty($producto['imagen'])): ?>
                    <img src="<?php echo $producto['imagen']; ?>" alt="Imagen del Producto" width="200px">
                <?php else: ?>
                    No hay imagen
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Subir Nueva Imagen</label>
                <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*"/>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a class="btn btn-secondary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>


