<?php
session_start();
include("../../bd.php");

// Verificar si se ha proporcionado el ID del producto y actualizar su estado a inactivo
if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];

    // Actualizar el campo "activo" a 0 para desactivar el producto
    $sentencia = $conexion->prepare("UPDATE productos SET activo = 0 WHERE id_producto = :id_producto");
    $sentencia->bindParam(":id_producto", $id_producto);
    $sentencia->execute();

    header("Location: index.php");
    exit(); 
}

// Obtener término de búsqueda del producto
$termino_busqueda = isset($_GET['search']) ? $_GET['search'] : '';

// Obtener el proveedor seleccionado para filtrar
$proveedor_seleccionado = isset($_GET['proveedor']) ? $_GET['proveedor'] : '';

// Consulta base para listar los productos activos
$sql = "
    SELECT p.*, c.categoria AS n_categoria, pr.nombre AS n_proveedor
    FROM productos p
    INNER JOIN categorias c ON p.categoria = c.idcategoria
    LEFT JOIN productos_has_proveedor php ON p.id_producto = php.productos_id_producto
    LEFT JOIN proveedor pr ON php.proveedor_id = pr.id
    WHERE p.activo = 1
";

// Aplicar filtros según los parámetros de búsqueda
if (!empty($termino_busqueda)) {
    $sql .= " AND p.nombre LIKE :termino_busqueda";
}

if (!empty($proveedor_seleccionado)) {
    $sql .= " AND pr.id = :proveedor_seleccionado";
}

// Preparar consulta SQL
$sentencia = $conexion->prepare($sql);

if (!empty($termino_busqueda)) {
    $sentencia->bindValue(':termino_busqueda', "%$termino_busqueda%", PDO::PARAM_STR);
}

if (!empty($proveedor_seleccionado)) {
    $sentencia->bindValue(':proveedor_seleccionado', $proveedor_seleccionado, PDO::PARAM_INT);
}

$sentencia->execute();
$lista_productos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de proveedores para el desplegable
$sentencia_proveedores = $conexion->prepare("SELECT id, nombre FROM proveedor");
$sentencia_proveedores->execute();
$proveedores = $sentencia_proveedores->fetchAll(PDO::FETCH_ASSOC);

include("../../templates/header.php");
?>

<!-- Filtro de búsqueda y selector de proveedores -->
<div class="mb-3">
    <form action="" method="get" class="form-inline">
        <div class="form-group mr-2">
            <label for="search" class="mr-2">Buscar Producto:</label>
            <input type="text" name="search" id="search" class="form-control" value="<?php echo htmlspecialchars($termino_busqueda); ?>" placeholder="Nombre del Producto">
        </div>
        <div class="form-group">
            <label for="proveedor" class="mr-2">Proveedor:</label>
            <select name="proveedor" id="proveedor" class="form-control">
                <option value="">Todos</option>
                <?php foreach ($proveedores as $proveedor) : ?>
                    <option value="<?php echo $proveedor['id']; ?>" <?php if ($proveedor['id'] == $proveedor_seleccionado) echo 'selected'; ?>><?php echo htmlspecialchars($proveedor['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary ml-2">Filtrar</button>
    </form>
</div>

<!-- Lista de productos -->
<div class="card">
    <div class="card-header">
        <a class="btn btn-primary" href="crear.php" role="button">Nuevo</a>
    </div>
    <div class="card-body">
        <div class="table-responsive-sm">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">Precio de Venta</th>
                        <th scope="col">Precio de Compra</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Fecha de Vencimiento</th>
                        <th scope="col">Fecha de Compra del Lote</th>
                        <th scope="col">Nombre del Proveedor</th>
                        <th scope="col">Imagen</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lista_productos)) : ?>
                        <?php foreach ($lista_productos as $registro) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($registro['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($registro['n_categoria']); ?></td>
                                <td><?php echo htmlspecialchars($registro['precio_venta']); ?></td>
                                <td><?php echo htmlspecialchars($registro['precio_compra']); ?></td>
                                <td><?php echo htmlspecialchars($registro['stock']); ?></td>
                                <td><?php echo htmlspecialchars($registro['fecha_vencimiento']); ?></td>
                                <td><?php echo htmlspecialchars($registro['fecha_compra_lote']); ?></td>
                                <td><?php echo htmlspecialchars($registro['n_proveedor']); ?></td>
                                <td>
                                    <?php if (!empty($registro['imagen'])) : ?>
                                        <img src="<?php echo htmlspecialchars($registro['imagen']); ?>" alt="Imagen del Producto" width="100" height="100">
                                    <?php else : ?>
                                        No hay imagen
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a name="Editar" id="Editar" class="btn btn-primary" href="editar.php?txtID=<?php echo $registro['id_producto']; ?>" role="button">EDITAR</a>
                                    <a name="Desactivar" id="Desactivar" class="btn btn-danger" href="index.php?id=<?php echo $registro['id_producto']; ?>" role="button">DESACTIVAR</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="11" class="text-center">No hay productos para mostrar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
