<?php 
include("../../bd.php");

// Obtener las categorías, unidades y proveedores
$sentencia_categorias = $conexion->prepare("SELECT * FROM categorias");
$sentencia_categorias->execute();
$categorias = $sentencia_categorias->fetchAll(PDO::FETCH_ASSOC);

$sentencia_unidades = $conexion->prepare("SELECT * FROM unidades");
$sentencia_unidades->execute();
$unidades = $sentencia_unidades->fetchAll(PDO::FETCH_ASSOC);

$sentencia_proveedores = $conexion->prepare("SELECT * FROM proveedor");
$sentencia_proveedores->execute();
$proveedores = $sentencia_proveedores->fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $categoria = isset($_POST["categoria"]) ? $_POST["categoria"] : "";
    $precio_compra = isset($_POST["precio_compra"]) ? $_POST["precio_compra"] : "";
    $precio_venta = isset($_POST["precio_venta"]) ? $_POST["precio_venta"] : "";
    $stock = isset($_POST["stock"]) ? $_POST["stock"] : "";
    $fecha_vencimiento = isset($_POST["fecha_vencimiento"]) ? $_POST["fecha_vencimiento"] : "";
    $fecha_compra_lote = isset($_POST["fecha_compra_lote"]) ? $_POST["fecha_compra_lote"] : "";
    $activo = isset($_POST["activo"]) ? $_POST["activo"] : "";
    $proveedor_id = isset($_POST["proveedor_id"]) ? $_POST["proveedor_id"] : "";
    $unidades_idunidades = isset($_POST["unidad"]) ? $_POST["unidad"] : "";

    $imagen = $_FILES['imagen']['name'];
    $imagen_temp = $_FILES['imagen']['tmp_name'];
    $carpeta_imagenes = "../../images/";
    $ruta_imagen = $carpeta_imagenes . basename($imagen);
    
    if (move_uploaded_file($imagen_temp, $ruta_imagen)) {
        echo "La imagen se subió correctamente.";
    } else {
        echo "Error al subir la imagen.";
    }

    // Insertar el nuevo producto
    $sentencia = $conexion->prepare("INSERT INTO productos (nombre, categoria, precio_compra, precio_venta, stock, fecha_vencimiento, fecha_compra_lote, Activo, imagen, unidades_idunidades) VALUES (:nombre, :categoria, :precio_compra, :precio_venta, :stock, :fecha_vencimiento, :fecha_compra_lote, :activo, :imagen, :unidades_idunidades)");
    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":categoria", $categoria);
    $sentencia->bindParam(":precio_compra", $precio_compra);
    $sentencia->bindParam(":precio_venta", $precio_venta);
    $sentencia->bindParam(":stock", $stock);
    $sentencia->bindParam(":fecha_vencimiento", $fecha_vencimiento);
    $sentencia->bindParam(":fecha_compra_lote", $fecha_compra_lote);
    $sentencia->bindParam(":activo", $activo);
    $sentencia->bindParam(":imagen", $ruta_imagen);
    $sentencia->bindParam(":unidades_idunidades", $unidades_idunidades);

    if ($sentencia->execute()) {
        // Obtener el ID del producto recién insertado
        $id_producto = $conexion->lastInsertId();

        // Insertar la relación en la tabla productos_has_proveedor
        $sentencia_relacion = $conexion->prepare("INSERT INTO productos_has_proveedor (productos_id_producto, proveedor_id) VALUES (:productos_id_producto, :proveedor_id)");
        $sentencia_relacion->bindParam(":productos_id_producto", $id_producto);
        $sentencia_relacion->bindParam(":proveedor_id", $proveedor_id);
        $sentencia_relacion->execute();

        header("Location: index.php");
        exit;
    } else {
        echo "Error al insertar el producto.";
    }
}

include("../../templates/header.php");
?>

</br>
<div class="card">
    <div class="card-header">
        NUEVO PRODUCTO
    </div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del producto" />
            </div>
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <select class="form-select" name="categoria" id="categoria">
                    <option selected>Seleccione una categoría</option>
                    <?php foreach($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['idcategoria']; ?>"><?php echo $categoria['categoria']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="unidades" class="form-label">Unidades</label>
                <select class="form-select" name="unidad" id="unidad">
                    <option selected>Seleccione una unidad</option>
                    <?php foreach($unidades as $unidad): ?>
                        <option value="<?php echo $unidad['idunidades']; ?>"><?php echo $unidad['unidad']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="precio_compra" class="form-label">Precio de compra</label>
                <input type="number" step="0.01" class="form-control" name="precio_compra" id="precio_compra" placeholder="Precio de compra del producto" />
            </div>
            <div class="mb-3">
                <label for="precio_venta" class="form-label">Precio de venta</label>
                <input type="number" step="0.01" class="form-control" name="precio_venta" id="precio_venta" placeholder="Precio de venta del producto" />
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" name="stock" id="stock" placeholder="Cantidad en stock" />
            </div>
            <div class="mb-3">
                <label for="fecha_vencimiento" class="form-label">Fecha de vencimiento</label>
                <input type="date" class="form-control" name="fecha_vencimiento" id="fecha_vencimiento" />
            </div>
            <div class="mb-3">
                <label for="fecha_compra_lote" class="form-label">Fecha de compra del lote</label>
                <input type="date" class="form-control" name="fecha_compra_lote" id="fecha_compra_lote" />
            </div>
            <div class="mb-3">
                <label for="activo" class="form-label">Activo</label>
                <select class="form-select" name="activo" id="activo">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="proveedor_id" class="form-label">Proveedor</label>
                <select class="form-select" name="proveedor_id" id="proveedor_id">
                    <option selected>Seleccione un proveedor</option>
                    <?php foreach($proveedores as $proveedor): ?>
                        <option value="<?php echo $proveedor['id']; ?>"><?php echo $proveedor['nombre']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen del producto</label>
                <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*" />
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php");?>
