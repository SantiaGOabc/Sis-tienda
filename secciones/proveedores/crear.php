<?php 
include("../../bd.php");

// Obtener los datos de empleado
$sentencia_empleados = $conexion->prepare("SELECT id_empleado, nombre, rol FROM empleados");
$sentencia_empleados->execute();
$lista_empleados = $sentencia_empleados->fetchAll(PDO::FETCH_ASSOC);

if($_POST) {
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $telefono = isset($_POST["telefono"]) ? $_POST["telefono"] : "";
    $direccion = isset($_POST["direccion"]) ? $_POST["direccion"] : "";
    $nombre_referencia = isset($_POST["nombre_referencia"]) ? $_POST["nombre_referencia"] : "";
    $NIT = isset($_POST["NIT"]) ? $_POST["NIT"] : "";
    $razon_social = isset($_POST["razon_social"]) ? $_POST["razon_social"] : "";
    $activo = isset($_POST["activo"]) ? $_POST["activo"] : "";

    // Preparar consulta SQL INSERT
    $consulta_insert = $conexion->prepare("INSERT INTO proveedor (nombre, telefono, direccion, nombre_referencia, NIT, `razon_social`, activo) 
                                           VALUES (:nombre, :telefono, :direccion, :nombre_referencia, :NIT, :razon_social, :activo)");

    // Ejecutar la consulta
    $resultado = $consulta_insert->execute(array(
        ':nombre' => $nombre,
        ':telefono' => $telefono,
        ':direccion' => $direccion,
        ':nombre_referencia' => $nombre_referencia,
        ':NIT' => $NIT,
        ':razon_social' => $razon_social,
        ':activo' => $activo
    ));

    // Verificar si la inserción fue exitosa
    if ($resultado) {
        header("Location: index.php");
        exit();
    } else {
        echo "Hubo un error al insertar los datos en la tabla proveedor.";
    }
}

include("../../templates/header.php");
?>

<br>
<div class="card">
    <div class="card-header">
        DATOS DEL PROVEEDOR
    </div>
    <div class="card-body">
        <form action="" method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="text" class="form-control" name="telefono" id="telefono">
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección:</label>
                <input type="text" class="form-control" name="direccion" id="direccion">
            </div>
            <div class="mb-3">
                <label for="nombre_referencia" class="form-label">Nombre de Referencia:</label>
                <input type="text" class="form-control" name="nombre_referencia" id="nombre_referencia">
            </div>
            <div class="mb-3">
                <label for="NIT" class="form-label">NIT:</label>
                <input type="text" class="form-control" name="NIT" id="NIT">
            </div>
            <div class="mb-3">
                <label for="razon_social" class="form-label">Razón Social:</label>
                <input type="text" class="form-control" name="razon_social" id="razon_social">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" name="activo" id="activo" value="1">
                <label class="form-check-label" for="activo">Activo</label>
            </div>
            <button type="submit" class="btn btn-primary">Agregar</button>
        </form>
    </div>
</div>
<?php include("../../templates/footer.php");?>
