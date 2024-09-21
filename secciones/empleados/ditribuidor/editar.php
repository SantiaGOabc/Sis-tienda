<?php
session_start();
include("../../../bd.php");

// Verificar si hay una sesión activa y obtener el nombre de usuario
$usuario_actual = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';

// Verificar si se ha enviado el formulario de edición
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_distribuidor = isset($_POST['id']) ? $_POST['id'] : "";
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : "";
    $telefono = isset($_POST["telefono"]) ? $_POST["telefono"] : "";
    $direccion = isset($_POST["direccion"]) ? $_POST["direccion"] : "";
    $correo = isset($_POST["correo"]) ? $_POST["correo"] : "";

    // Preparar la consulta SQL UPDATE
    $consulta_update = $conexion->prepare("UPDATE empleados SET nombre = :nombre, telefono = :telefono, direccion = :direccion, correo = :correo WHERE id_empleado = :id");

    // Ejecutar la consulta
    $resultado = $consulta_update->execute(array(
        ':nombre' => $nombre,
        ':telefono' => $telefono,
        ':direccion' => $direccion,
        ':correo' => $correo,
        ':id' => $id_distribuidor
    ));

    // Verificar si la actualización fue exitosa
    if ($resultado) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error al actualizar el distribuidor.";
    }
}

// Verificar si se proporcionó un ID de distribuidor para editar
if (isset($_GET['id'])) {
    $id_distribuidor = $_GET['id'];

    // Consultar los datos del distribuidor a editar
    $consulta_distribuidor = $conexion->prepare("SELECT * FROM empleados WHERE id_empleado = :id");
    $consulta_distribuidor->execute(array(':id' => $id_distribuidor));
    $distribuidor = $consulta_distribuidor->fetch(PDO::FETCH_ASSOC);

    if (!$distribuidor) {
        echo "No se encontró el distribuidor.";
        exit();
    }
} else {
    echo "ID de distribuidor no proporcionado.";
    exit();
}

include("../../../templates/header.php");
?>

<!-- Formulario de Edición -->
<div class="card">
    <div class="card-header">
        Editar Distribuidor
    </div>
    <div class="card-body">
        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $id_distribuidor; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $distribuidor['nombre']; ?>">
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo $distribuidor['telefono']; ?>">
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo $distribuidor['direccion']; ?>">
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" class="form-control" name="correo" id="correo" value="<?php echo $distribuidor['correo']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a class="btn btn-secondary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
</div>
