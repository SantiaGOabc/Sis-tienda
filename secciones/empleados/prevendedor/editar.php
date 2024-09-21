<?php
include("../../../bd.php");
include("../../../templates/header.php");

// Verificar si se ha enviado el formulario mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario y realizar la actualización
    $id_prevendedor = $_POST['id_prevendedor'];
    $id_empleado = $_POST['Empleado'];
    $id_ruta = $_POST['Ruta'];
    $fecha_asignacion = $_POST['Fecha_de_Asignacion'];
    $fecha_visita = $_POST['Fecha_de_Visita'];

    // Preparar la consulta SQL UPDATE
    $consulta_update = $conexion->prepare("UPDATE prevendedor SET datos = :datos, ruta = :ruta, fecha_asignacion = :fecha_asignacion, fecha_visita = :fecha_visita WHERE idprevendedor = :id_prevendedor");

    // Ejecutar la consulta
    $resultado = $consulta_update->execute(array(
        ':datos' => $id_empleado,
        ':ruta' => $id_ruta,
        ':fecha_asignacion' => $fecha_asignacion,
        ':fecha_visita' => $fecha_visita,
        ':id_prevendedor' => $id_prevendedor
    ));

    // Verificar si la actualización fue exitosa
    if ($resultado) {
        echo "Los datos del prevendedor se han actualizado correctamente.";
    } else {
        echo "Hubo un error al actualizar los datos del prevendedor.";
    }
}

// Obtener el ID del prevendedor a editar
if (isset($_GET['id'])) {
    $id_prevendedor = $_GET['id'];

    // Consultar los datos del prevendedor a editar
    $consulta_prevendedor = $conexion->prepare("SELECT * FROM prevendedor WHERE idprevendedor = :id");
    $consulta_prevendedor->execute(array(':id' => $id_prevendedor));
    $prevendedor = $consulta_prevendedor->fetch(PDO::FETCH_ASSOC);

    if (!$prevendedor) {
        echo "No se encontró el prevendedor.";
        exit();
    }
} else {
    echo "ID de prevendedor no proporcionado.";
    exit();
}

// Consulta para obtener la lista de rutas y empleados
$sentencia_ruta = $conexion->prepare("SELECT * FROM `rutas`");
$sentencia_ruta->execute();
$rutas = $sentencia_ruta->fetchAll(PDO::FETCH_ASSOC);

$sentencia_empleado = $conexion->prepare("SELECT e.*, r.rol AS nombre_rol FROM `empleados` e INNER JOIN `rol` r ON e.rol = r.idrol WHERE r.idrol = 3");
$sentencia_empleado->execute();
$empleados = $sentencia_empleado->fetchAll(PDO::FETCH_ASSOC);

include("../../../templates/header.php");
?>

<!-- Formulario de Edición -->
<div class="card">
    <div class="card-header">
        Editar Prevendedor
    </div>
    <div class="card-body">
        <form action="" method="post">
            <input type="hidden" name="id_prevendedor" value="<?php echo $id_prevendedor; ?>">
            <div class="form-group">
                <label for="Empleado" class="form-label">Empleado</label>
                <select class="form-control" name="Empleado" id="Empleado">
                    <?php foreach ($empleados as $empleado): ?>
                        <option value="<?php echo $empleado['id_empleado']; ?>" <?php if ($empleado['id_empleado'] == $prevendedor['datos']) echo "selected"; ?>><?php echo $empleado['nombre'] . ' ' . $empleado['apellido']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="Ruta" class="form-label">Ruta</label>
                <select class="form-control" name="Ruta" id="Ruta">
                    <?php foreach ($rutas as $ruta): ?>
                        <option value="<?php echo $ruta['idruta']; ?>" <?php if ($ruta['idruta'] == $prevendedor['ruta']) echo "selected"; ?>><?php echo $ruta['codigo'] . ' - ' . $ruta['municipio']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="Fecha_de_Asignacion" class="form-label">Fecha de Asignación</label>
                <input type="date" class="form-control" name="Fecha_de_Asignacion" id="Fecha_de_Asignacion" value="<?php echo $prevendedor['fecha_asignacion']; ?>">
            </div>
            <div class="form-group">
                <label for="Fecha_de_Visita" class="form-label">Fecha de Visita</label>
                <input type="date" class="form-control" name="Fecha_de_Visita" id="Fecha_de_Visita" value="<?php echo $prevendedor['fecha_visita']; ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a class="btn btn-secondary" href="index.php" role="button">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php include("../../../templates/footer.php");?>
